<?php

namespace controleur;

use vue\base\MainTemplate as Vue;
use modele\User;
use app\util\Request as req;
use app\util\SessionLogin as UserSession;



class Login {
    public function __construct(){
        
        $erreur = null;
       
        $userMail = req::post('mail');
        $userPassword = req::post('password');

        // On vérifie si l'une des clés existe dans la requête (envoie du formulaire)
        // Request::is() remplace isset($_POST['mail'])
        if(req::is('mail') || req::is('password')) {
            // Vérification des champs vides
            if(empty($userMail) && empty($userPassword)){
                $erreur = "Veuillez remplir tous les champs.";
            }
            else{
                // Appel au modèle pour la vérification
                $user = User::verifIdentifiant($userMail, $userPassword);
                if($user){
                    // Si user est good on enregistre le role et l'objet entier en session
                    UserSession::loginWithRole($user->codeRole);
                    $_SESSION['user'] = $user;

                    // REDIRECTION
                    header('Location: accueil');
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