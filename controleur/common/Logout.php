<?php

namespace controleur\common;

use app\util\SessionLogin;
use app\util\BaseURL;

/**
 * CONTRÔLEUR : Logout
 * Déconnexion de l'utilisateur courant.
 * Supprime les clés de session (LOGIN, ROLE) via SessionLogin,
 * détruit la session PHP, puis redirige vers la page de connexion.
 * Aucune vue rendue — redirection immédiate.
 */
class Logout {

    public function __construct() {
        SessionLogin::logout();   // supprime $_SESSION['LOGIN'] et $_SESSION['ROLE']
        session_destroy();        // invalide la session côté serveur
        header('Location: ' . BaseURL::getBaseUrl() . 'login');
        exit;
    }
}
