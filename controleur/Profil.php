<?php

namespace controleur;

use app\util\Request;
use app\util\Guard;
use vue\base\MainTemplate as Vue;
use modele\DAO\UserDAO;

class Profil {

    public function __construct() {
        Guard::requireLogin();

        $user    = $_SESSION['user'] ?? null;
        $success = null;
        $error   = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user !== null) {
            $action  = $_POST['action'] ?? '';
            $userDAO = new UserDAO();
            $metier  = $userDAO->getUsersById($user->id);

            if ($action === 'identity') {
                $name    = Request::post('name');
                $surname = Request::post('surname');

                if (empty($name) || empty($surname)) {
                    $error = 'Le prénom et le nom ne peuvent pas être vides.';
                } else {
                    $metier->setName($name)->setSurname($surname);
                    $success = $userDAO->update($metier);
                    if ($success) {
                        $user->name    = $name;
                        $user->surname = $surname;
                        $_SESSION['user'] = $user;
                    }
                }

            } elseif ($action === 'password') {
                $current  = Request::post('current_password');
                $new      = Request::post('new_password');
                $confirm  = Request::post('confirm_password');

                if (!password_verify($current, $metier->getPassword())) {
                    $error = 'Mot de passe actuel incorrect.';
                } elseif (empty($new)) {
                    $error = 'Le nouveau mot de passe ne peut pas être vide.';
                } elseif ($new !== $confirm) {
                    $error = 'Les mots de passe ne correspondent pas.';
                } else {
                    $metier->setPassword($new);
                    $success = $userDAO->update($metier);
                }
            }
        }

        Vue::setTitle('Mon Profil');
        Vue::render('Profil', [
            'user'    => $user,
            'success' => $success,
            'error'   => $error,
        ]);
    }
}
