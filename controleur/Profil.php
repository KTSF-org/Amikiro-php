<?php

namespace controleur;

use app\util\Request;
use app\util\Guard;
use vue\base\MainTemplate as Vue;
use modele\DAO\UserDAO;
use app\util\SessionLogin as UserSession;

class Profil {

    public function __construct() {
        Guard::requireLogin();

        
        $success = null;
        $error   = null;

        $id = UserSession::getUserId();
        $role = UserSession::getRole();
        $userDAO = new UserDAO();
        $user  = $userDAO->getUsersById($id);
        $surname = $user->getSurname();
        $name = $user->getName();
        $mail = $user->getMail();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $action  = $_POST['action'] ?? '';

            if ($action === 'identity') {
                $namePost    = Request::post('name');
                $surnamePost = Request::post('surname');

                if (empty($namePost) || empty($surnamePost)) {
                    $error = 'Le prénom et le nom ne peuvent pas être vides.';
                } else {
                    $user->setName($namePost)->setSurname($surnamePost);
                    $success = $userDAO->update($user);
                    if ($success) {
                        $name    = $namePost;
                        $surname = $surnamePost;
                        
                    }
                }

            } elseif ($action === 'password') {
                $current  = Request::post('current_password');
                $new      = Request::post('new_password');
                $confirm  = Request::post('confirm_password');

                if (!password_verify($current, $user->getPassword())) {
                    $error = 'Mot de passe actuel incorrect.';
                } elseif (empty($new)) {
                    $error = 'Le nouveau mot de passe ne peut pas être vide.';
                } elseif ($new !== $confirm) {
                    $error = 'Les mots de passe ne correspondent pas.';
                } else {
                    $user->setPassword($new);
                    $success = $userDAO->update($user);
                }
            }
        }

        Vue::setTitle('Mon Profil');
        Vue::render('Profil', [
            'surname' => $surname,
            'name'    => $name,
            'role'    => $role,
            'mail'    => $mail,
            'success' => $success,
            'error'   => $error,
        ]);
    }
}
