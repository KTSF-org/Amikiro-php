<?php

namespace controleur;

use modele\DAO\UserDAO;
use app\util\SessionLogin;
use app\util\BaseURL;

// Backdoor de dev uniquement — à retirer avant mise en production.
// Usage : /dev/login?role=0 (invité), ?role=1 (adhérent), ?role=2 (naturaliste), ?role=3 (admin)
// Sans paramètre : connecte le premier compte admin trouvé en base.
class DevLogin {

    public function __construct() {
        $role    = isset($_GET['role']) ? (int)$_GET['role'] : ROLE_ADMIN;
        $userDAO = new UserDAO();
        $users   = $userDAO->getAllFiltered($role, 0, 1);

        if (empty($users)) {
            die('DevLogin : aucun compte trouvé pour le rôle ' . $role);
        }

        $user = $users[0];
        SessionLogin::loginWithRole($user->codeRole, $user->id);

        header('Location: ' . BaseURL::getBaseUrl() . 'accueil');
        exit;
    }
}
