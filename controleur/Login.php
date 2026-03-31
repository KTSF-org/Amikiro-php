<?php

namespace controleur;

use vue\base\MainTemplate as Vue;
use modele\User;
use modele\DAO\UserDAO;

class Login {
    public function __construct(){
        
        // Si déjà connecté
        // if (isset($_SESSION['user'])) {
        //     header('Location: /accueil');
        //     exit;
        // }

        $erreur = null;

        if (isset($_POST['email']) && isset($_POST['pwd'])) {
            $email = trim($_POST['email']);
            $pwd = trim($_POST['pwd']);

            if (empty($email)) {
                $erreur = "Email obligatoire";
            } elseif (empty($pwd)) {
                $erreur = "Mot de passe obligatoire";
            } else {
                $user = User::verifIdentifiant($email, $pwd);

                // if ($user) {
                //     $_SESSION['user'] = $user;
                //     header('Location: /accueil');
                //     exit;
                // }
                $erreur = "Email ou mot de passe incorrect";
            }
        }

        Vue::setTitle('Connexion');
        Vue::addCSS([
            ASSET. '/css/login.css',
        ]);

        Vue::render('Login', ['erreur' -> $erreur], '', true);

        
        
    }
    
}

?>