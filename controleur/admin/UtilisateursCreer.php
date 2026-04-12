<?php

namespace controleur\admin;

use vue\base\MainTemplate as Vue;
use app\util\Guard;
use app\util\BaseURL;
use app\util\Request;
use modele\User;
use modele\DAO\UserDAO;

class UtilisateursCreer {

    public function __construct() {
        Guard::requireRole(ROLE_ADMIN);

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name      = Request::post('name');
            $surname   = Request::post('surname');
            $mail      = Request::post('mail');
            $password  = Request::post('password');
            $codeRole  = (int)($_POST['codeRole'] ?? ROLE_ADHERENT);
            $memberNum = (int)($_POST['memberNum'] ?? 0);

            if ($codeRole === ROLE_ADMIN) {
                $error = 'Impossible de créer un compte administrateur.';
            } elseif (empty($name) || empty($surname) || empty($mail) || empty($password)) {
                $error = 'Tous les champs obligatoires doivent être remplis.';
            } else {
                // 'tmp' évite l'exception de checkModelArgs sur password vide ;
                // setPassword() le remplace immédiatement par le hash réel.
                $metier = new User($codeRole, $mail, 0, 'tmp', $name, $surname, 0, $memberNum);
                $metier->setPassword($password);

                $userDAO = new UserDAO();
                if ($userDAO->create($metier)) {
                    header('Location: ' . BaseURL::getBaseUrl() . 'parametres/utilisateurs');
                    exit;
                }
                $error = 'Erreur lors de la création du compte.';
            }
        }

        Vue::setTitle('Créer un compte');
        Vue::render('admin/UtilisateursCreer', ['error' => $error]);
    }
}
