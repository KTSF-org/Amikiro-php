<?php

namespace controleur;

use modele\DAO\UserDAO;
use app\util\SessionLogin;
use app\util\BaseURL;

class DevLogin {

    private const ADMIN_MAIL = 'florian@gmail.com';

    public function __construct() {
        $userDAO = new UserDAO();
        $user    = $userDAO->getUserByEmail(self::ADMIN_MAIL);

        if (!$user) {
            die('DevLogin : compte introuvable (' . self::ADMIN_MAIL . ')');
        }

        $_SESSION['user'] = $user;
        SessionLogin::loginWithRole($user->codeRole, $user->id);

        header('Location: ' . BaseURL::getBaseUrl() . 'accueil');
        exit;
    }
}
