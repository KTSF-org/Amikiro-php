<?php

namespace controleur\admin;

use vue\base\MainTemplate as Vue;
use app\util\Guard;
use app\util\BaseURL;
use modele\DAO\UserDAO;

class Utilisateurs {

    public function __construct() {
        Guard::requireRole(ROLE_ADMIN);

        $userDAO = new UserDAO();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
            $id        = (int)($_POST['id'] ?? 0);
            $currentId = (int)($_SESSION['user']->id ?? 0);
            if ($id > 0 && $id !== $currentId) {
                $metier = $userDAO->getUsersById($id);
                $metier->deleteUser();
            }
            header('Location: ' . BaseURL::getBaseUrl() . 'parametres/utilisateurs');
            exit;
        }

        $users     = $userDAO->getAll();
        $currentId = (int)($_SESSION['user']->id ?? 0);

        Vue::setTitle('Gestion des utilisateurs');
        Vue::render('admin/Utilisateurs', [
            'users'     => $users,
            'currentId' => $currentId,
        ]);
    }
}
