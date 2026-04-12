<?php

namespace controleur\admin;

use vue\base\MainTemplate as Vue;
use app\util\Guard;
use app\util\BaseURL;
use app\util\Request;
use modele\DAO\UserDAO;

class UtilisateursEditer {

    public function __construct() {
        Guard::requireRole(ROLE_ADMIN);

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ' . BaseURL::getBaseUrl() . 'parametres/utilisateurs');
            exit;
        }

        $userDAO   = new UserDAO();
        $metier    = $userDAO->getUsersById($id);
        $currentId = (int)($_SESSION['user']->id ?? 0);
        $isSelf    = ($id === $currentId);
        $error     = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name      = Request::post('name');
            $surname   = Request::post('surname');
            $mail      = Request::post('mail');
            $password  = Request::post('password');
            $memberNum = (int)($_POST['memberNum'] ?? $metier->getMemberNum());
            // Le rôle de l'admin connecté ne peut pas être modifié
            $codeRole  = $isSelf ? $metier->getCodeRole() : (int)($_POST['codeRole'] ?? $metier->getCodeRole());

            if (!$isSelf && $codeRole === ROLE_ADMIN) {
                $error = 'Impossible d\'attribuer le rôle administrateur.';
            } elseif (empty($name) || empty($surname) || empty($mail)) {
                $error = 'Tous les champs obligatoires doivent être remplis.';
            } else {
                $metier->setName($name)
                       ->setSurname($surname)
                       ->setMail($mail)
                       ->setCodeRole($codeRole)
                       ->setMemberNum($memberNum);

                if (!empty($password)) {
                    $metier->setPassword($password);
                }

                if ($userDAO->update($metier)) {
                    header('Location: ' . BaseURL::getBaseUrl() . 'parametres/utilisateurs');
                    exit;
                }
                $error = 'Erreur lors de la mise à jour du compte.';
            }
        }

        Vue::setTitle('Modifier un compte');
        Vue::render('admin/UtilisateursEditer', [
            'metier'  => $metier,
            'error'   => $error,
            'isSelf'  => $isSelf,
        ]);
    }
}
