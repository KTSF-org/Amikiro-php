<?php

namespace controleur;


use vue\base\MainTemplate as Vue;
use modele\User;
use modele\DAO\UserDAO;
use modele\DAO\AbonnementDAO;
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
 * Logique d'abonnement à la connexion :
 *   - Applicable uniquement aux comptes dont le rôle est ROLE_ADHERENT.
 *   - Si aucun abonnement actif n'est trouvé en base, le rôle est rétrogradé
 *     à ROLE_INVITE en BDD et dans la session courante.
 *   - ROLE_NATURALISTE n'est pas affecté : son abonnement est administratif,
 *     non lié au contrôle d'accès.
 */



class Login {
    public function __construct(){
        // Redirige l'utilisateur sur la page d'accueil
        // si il tente d'aller sur la page de login alors qu'il est deja co
        if (UserSession::isLogin()){
            header('Location: accueil');
            exit;
        }

        $erreur = null;

        $userMail = req::post('mail');
        $userPassword = req::post('password');
        $saisie_captcha = req::post('captcha_code');

        // On vérifie si l'une des clés existe dans la requête (envoie du formulaire)
        // Request::is() remplace isset($_POST['mail'])
        if(req::is('mail') || req::is('password')) {
            // On nettoie la saisie pour correspondre au format de Render
            $nettoyage = strtolower(str_replace(' ', '', $saisie_captcha));
            $saisieMd5 = md5($nettoyage);
            // Vérification des champs vides
            if(empty($userMail) || empty($userPassword || empty($saisie_captcha))){
                $erreur = "Veuillez remplir tous les champs.";
            }
            elseif(!isset($_SESSION["captchaCode"]) || $saisieMd5 !== $_SESSION["captchaCode"]){
                $erreur = "Captcha incorrect";
            }
            else{
                // Appel au modèle pour la vérification
                $user = User::verifIdentifiant($userMail, $userPassword);
                if($user){
                    // Vérification abonnement expiré uniquement pour ROLE_ADHERENT
                    if ((int)$user->codeRole === ROLE_ADHERENT) {
                        $abonnementDAO = new AbonnementDAO();
                        if (!$abonnementDAO->getActiveByUser($user->id)) {
                            $userDAO = new UserDAO();
                            $metier  = $userDAO->getUsersById($user->id);
                            $metier->setCodeRole(ROLE_INVITE);
                            $userDAO->update($metier);
                            $user->codeRole = ROLE_INVITE;
                        }
                    }

                    // Enleve la session captcha
                    unset($_SESSION["captchaCode"]);

                    // Si user est good on enregistre le role et l'objet entier en session
                    UserSession::loginWithRole($user->codeRole, $user->id);
                    // REDIRECTION
                    header('Location:  accueil');
                    exit;
                }else {
                    // Dernier cas d'echec : soit mail inconnu ou password
                    $erreur = "Email ou mot de passe incorrect.";
                }
            }
        }





        Vue::setTitle('Connexion');
        Vue::addCSS([
            ASSET. '/css/login.css',
        ]);

        Vue::render('Login', ['erreur' => $erreur],'', true);

      

    }

}

?>
