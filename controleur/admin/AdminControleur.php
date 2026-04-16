<?php

namespace controleur\admin;

use app\util\Request as req;
use app\util\SessionLogin as UserSession;
use vue\base\MainTemplate as Vue;
use modele\User;


class AdminControleur
{
    public function __construct()
    {

        if (UserSession::isLogin()){
            header('Location: accueil');
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

                    if ($user) {
                        // Si user est good on enregistre le role et l'id en session
                        UserSession::loginWithRole($user->codeRole, $user->id);
                        // REDIRECTION
                        header('Location:  accueil');
                        exit;
                    } else {
                        // Dernier cas d'echec : soit mail inconnu ou password
                        $erreur = "Email ou mot de passe incorrect.";
                    }
                }
            }
        }

        Vue::addCSS([
            ASSET. '/css/login.css',
        ]);

        Vue::render('admin/Admin', ['erreur' => $erreur],'', true);
    }

}


?>