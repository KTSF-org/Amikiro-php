<?php

namespace controleur\admin;

use app\util\Request as req;
use app\util\SessionLogin as UserSession;
use app\util\BaseURL;
use vue\base\MainTemplate as Vue;
use modele\User;


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

        
        $uri = BASENAME($_SERVER['REQUEST_URI']);

        // verifie si l'url est bien celle spécifique à l'admin
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