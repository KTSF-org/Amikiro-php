<?php

namespace controleur;

use modele\DAO\UserDAO;
use vue\base\MainTemplate as Vue;
use modele\User;
use app\util\Request;
use app\util\SessionLogin as UserSession;



class Login {
    public function __construct(){
        
        $erreur = null;
        $user = null;
        // Recupere l'email
        if (isset($_POST['mail']) && !empty($_POST['mail'])){
            $userMail = $_POST['mail'];
        }

        // Recupere le mdp
        if (isset($_POST['password']) && !empty($_POST['password'])){
            $userPassword = $_POST['password'];
        }

        // variable $user récupere toute les donnees du user dans la bdd
        if(isset($userMail) && isset($userPassword)) {
            $user = User::verifIdentifiant($userMail, $userPassword);
        }

        if($user){
            // $_SESSION['user'] = $user;
            UserSession::login();
            header("Location: accueil");
            exit;
        }

        

        Vue::setTitle('Connexion');
        Vue::addCSS([
            ASSET. '/css/login.css',
        ]);

        Vue::render('Login', ['erreur' => $erreur],'', true);

        
        
    }
    
}

?>