<?php

namespace controleur\common;


use vue\base\MainTemplate as Vue;
use modele\User;
use modele\DAO\UserDAO;
use modele\DAO\SubscriptionDAO;
use modele\DAO\ConfigDAO;
use app\util\Request as req;
use app\util\SessionLogin as UserSession;

/**
 * CONTRÔLEUR : Login
 * Gestion de l'authentification.
 *
 * Sur POST : vérifie email + mot de passe via User::verifIdentifiant(),
 *   contrôle l'abonnement pour les comptes ROLE_ADHERENT,
 *   stocke l'objet utilisateur en session, enregistre le rôle via SessionLogin,
 *   puis redirige vers /accueil.
 * Sur GET (ou POST invalide) : affiche le formulaire avec un éventuel message d'erreur.
 *
 * Logique de temps d'accès à la connexion :
 *   - ROLE_ADHERENT sans temps d'accès actif : rétrogradé en ROLE_INVITE avec un temps
 *     d'accès par défaut (guestDefaultAccessDays depuis Config), puis connecté.
 *   - ROLE_NATURALISTE sans temps d'accès actif : même mécanique que ROLE_ADHERENT.
 *   - ROLE_INVITE sans temps d'accès actif : connexion refusée.
 *   - ROLE_ADMIN : route séparée en production, bloqué ici intentionnellement.
 */



class Login
{
    public function __construct()
    {
        // Redirige l'utilisateur sur la page d'accueil
        // si il tente d'aller sur la page de login alors qu'il est deja co
        if (UserSession::isLogin()) {
            header('Location: accueil');
            exit;
        }

        $erreur = null;

        $userMail = req::post('mail');
        $userPassword = req::post('password');
        $saisie_captcha = req::post('captcha_code');

        // On vérifie si l'une des clés existe dans la requête (envoie du formulaire)
        // Request::is() remplace isset($_POST['mail'])
        if (req::is('mail') || req::is('password')) {
            // On nettoie la saisie pour correspondre au format de Render
            $nettoyage = strtolower(str_replace(' ', '', $saisie_captcha));
            $saisieMd5 = md5($nettoyage);
            // Vérification des champs vides
            if (empty($userMail) || empty($userPassword || empty($saisie_captcha))) {
                $erreur = "Veuillez remplir tous les champs.";
            } elseif (!isset($_SESSION["captchaCode"]) || $saisieMd5 !== $_SESSION["captchaCode"]) {
                $erreur = "Captcha incorrect";
            } else {
                // Appel au modèle pour la vérification
                $user = User::verifIdentifiant($userMail, $userPassword);
                if ($user) {
                    $role    = (int) $user->codeRole;
                    $userDAO = new UserDAO();

                    if ($role === ROLE_NATURALISTE || $role === ROLE_ADHERENT) {
                        $subscriptionDAO = new SubscriptionDAO();
                        $userId        = (int)$user->id;
                        $effectiveRole = $role;
                        if (!$subscriptionDAO->getActiveByUser($userId)) {
                            // Accès expiré : rétrograde en invité avec une période par défaut.
                            $config = (new ConfigDAO())->getConfig();
                            $days   = (int)($config->guestDefaultAccessDays ?? 7);
                            $subscriptionDAO->createForUser(
                                $userId,
                                date('Y-m-d'),
                                date('Y-m-d', strtotime("+$days days"))
                            );
                            $userObj = $userDAO->getUsersById($userId);
                            $userObj->setCodeRole(ROLE_INVITE);
                            $userDAO->update($userObj);
                            $effectiveRole = ROLE_INVITE;
                        }
                        unset($_SESSION["captchaCode"]);
                        $userDAO->incrementConnectCount($userId);
                        UserSession::loginWithRole($effectiveRole, $userId);
                        header('Location:  live');
                        exit;

                    } elseif ($role === ROLE_INVITE) {
                        // Vérification du temps d'accès pour les invités
                        $subscriptionDAO = new SubscriptionDAO();
                        if (!$subscriptionDAO->getActiveByUser($user->id)) {
                            $erreur = "Votre temps d'accès a expiré. Contactez un administrateur.";
                        } else {
                            unset($_SESSION["captchaCode"]);
                            $userDAO->incrementConnectCount($user->id);
                            // Si user est good on enregistre le role et l'objet entier en session
                            UserSession::loginWithRole($user->codeRole, $user->id);
                            // REDIRECTION
                            header('Location:  live');
                            exit;
                        }

                    } else {
                        // ROLE_ADMIN : route séparée en production, bloqué ici intentionnellement
                        $erreur = "Email ou mot de passe incorrect";
                    }
                } else {
                    // Dernier cas d'echec : soit mail inconnu ou password
                    $erreur = "Email ou mot de passe incorrect.";
                }
            }
        }
        Vue::setTitle('Connexion');
        Vue::addCSS([
            ASSET . '/css/login.css',
        ]);

        Vue::render('Login', ['erreur' => $erreur], '', true);
    }
}

?>
