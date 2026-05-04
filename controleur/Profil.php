<?php

namespace controleur;

use app\util\Request;
use app\util\Guard;
use vue\base\MainTemplate as Vue;
use modele\DAO\UserDAO;
use app\util\SessionLogin as UserSession;

/**
 * CONTRÔLEUR : Profil
 * Permet à tout utilisateur connecté de modifier son prénom/nom et son mot de passe.
 *
 * Deux actions POST dispatché via $_POST['action'] :
 *   identity — met à jour name et surname (email non modifiable par l'utilisateur)
 *   password — change le mot de passe après vérification de l'actuel
 */
class Profil {

    public function __construct() {
        Guard::requireLogin();

        $success = null;
        $error   = null;

        $id      = UserSession::getUserId();
        $role    = UserSession::getRole();
        $userDAO = new UserDAO();
        // getUsersById() retourne un objet User avec propriétés privées — utiliser les getters
        $user    = $userDAO->getUsersById($id);
        $surname = $user->getSurname();
        $name    = $user->getName();
        $mail    = $user->getMail();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $action = $_POST['action'] ?? '';

            if ($action === 'identity') {
                $namePost    = Request::post('name');
                $surnamePost = Request::post('surname');

                if (empty($namePost) || empty($surnamePost)) {
                    $error = 'Le prénom et le nom ne peuvent pas être vides.';
                } else {
                    $user->setName($namePost)->setSurname($surnamePost);
                    $success = $userDAO->update($user);
                    // Mise à jour locale pour que la vue affiche les nouvelles valeurs sans rechargement BDD
                    if ($success) {
                        $name    = $namePost;
                        $surname = $surnamePost;
                    }
                }

            } elseif ($action === 'password') {
                $current = Request::post('current_password');
                $new     = Request::post('new_password');
                $confirm = Request::post('confirm_password');

                // Vérification de l'actuel avant d'accepter le changement — évite qu'une session volée
                // puisse changer le mot de passe sans connaître l'original.
                if (!password_verify($current, $user->getPassword())) {
                    $error = 'Mot de passe actuel incorrect.';
                } elseif (empty($new)) {
                    $error = 'Le nouveau mot de passe ne peut pas être vide.';
                } elseif ($new !== $confirm) {
                    $error = 'Les mots de passe ne correspondent pas.';
                } else {
                    // setPassword() hache automatiquement via password_hash() (bcrypt cost 12)
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
