<?php

namespace controleur;

use app\util\Request;
use app\util\Guard;
use vue\base\MainTemplate as Vue;
use modele\DAO\UserDAO;

/**
 * CONTRÔLEUR : Profil
 * Affichage et modification du profil de l'utilisateur connecté.
 * Sur POST : met à jour name et surname via UserDAO, puis rafraîchit $_SESSION['user'].
 * Sur GET : affiche le formulaire pré-rempli avec les données de session.
 * Dépend de $_SESSION['user'] — aucune action si non connecté.
 */
class Profil {

    public function __construct() {
        Guard::requireLogin();

        $user    = $_SESSION['user'] ?? null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user !== null) {
            $name    = Request::post('name');
            $surname = Request::post('surname');

            // Chargement de l'objet métier User depuis la BDD pour l'update
            $userDAO = new UserDAO();
            $metier  = $userDAO->getUsersById($user->id);
            $metier->setName($name)->setSurname($surname);
            $success = $userDAO->update($metier);

            // Mise à jour de la session pour refléter les nouvelles valeurs
            if ($success) {
                $user->name    = $name;
                $user->surname = $surname;
                $_SESSION['user'] = $user;
            }
        }

        Vue::setTitle('Mon Profil');

        Vue::render('Profil', [
            'user'    => $user,
            'success' => $success,
        ]);
    }
}
