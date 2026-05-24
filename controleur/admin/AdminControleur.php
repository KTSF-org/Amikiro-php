<?php

namespace controleur\admin;

use app\util\Request as req;
use app\util\SessionLogin as UserSession;
use app\util\BaseURL;
use vue\base\MainTemplate as Vue;
use modele\User;

/**
 * Contrôleur : formulaire de connexion réservé à l'administrateur.
 *
 * Cette page n'est pas la page de login classique (/login).
 * Elle est accessible uniquement via l'URL secrète définie par la constante
 * URL_ADMIN (app/const.php). Seul un compte dont le codeRole vaut ROLE_ADMIN
 * peut s'authentifier ici — un adhérent ou naturaliste qui connaîtrait l'URL
 * se verrait refuser l'accès.
 *
 * Flux :
 *   GET  → affiche le formulaire de connexion admin.
 *   POST → vérifie les identifiants et le rôle, puis redirige vers /accueil.
 */
class AdminControleur
{
    public function __construct()
    {

        // Déjà connecté : redirige vers l'accueil sans afficher le formulaire
        if (UserSession::isLogin()){
            header('Location: ' . BaseURL::getBaseUrl() . 'accueil');
            exit;
        }

        $erreur = null;
        $userMail = req::post('mail');
        $userPassword = req::post('password');

        
        // basename() extrait le dernier segment de l'URL (ex: "/df6hj98d24vp" → "df6hj98d24vp").
        // On compare avec URL_ADMIN pour s'assurer que la soumission vient bien de la bonne page
        // et non d'une autre route qui instancierait ce contrôleur par erreur.
        $uri = basename($_SERVER['REQUEST_URI']);

        // Traitement du formulaire uniquement si l'URL correspond à celle de l'admin
        if ($uri === URL_ADMIN) {
            if (req::is('mail') || req::is('password')) {
                if (empty($userMail) || empty($userPassword)) {
                    $erreur = "Veuillez remplir tous les champs.";
                } else {
                    $user = User::verifIdentifiant($userMail, $userPassword);

                    // Seul un compte ROLE_ADMIN peut s'authentifier par cette route
                    if ($user && (int)$user->codeRole === ROLE_ADMIN) {
                        UserSession::loginWithRole($user->codeRole, $user->id);
                        header('Location: ' . BaseURL::getBaseUrl() . 'accueil');
                        exit;
                    } else {
                        // Échec : identifiants invalides ou rôle insuffisant
                        $erreur = "Email ou mot de passe incorrect.";
                    }
                }
            }
        }

        Vue::addCSS([
            ASSET. '/css/admin.css',
        ]);

        Vue::render('admin/Admin', ['erreur' => $erreur],'', true);
    }

}


?>