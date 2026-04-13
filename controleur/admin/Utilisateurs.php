<?php

namespace controleur\admin;

use vue\base\MainTemplate as Vue;
use app\util\Guard;
use app\util\BaseURL;
use modele\DAO\UserDAO;

/**
 * CONTRÔLEUR : Admin / Utilisateurs
 * Affiche la liste complète des comptes et gère la suppression.
 * Accès restreint à ROLE_ADMIN.
 *
 * Sur POST (action=delete) : supprime le compte ciblé après vérification
 *   que l'identifiant cible est différent de celui de la session courante,
 *   puis redirige vers la liste.
 * Sur GET : charge tous les utilisateurs et les transmet à la vue.
 */
class Utilisateurs {

    public function __construct() {
        Guard::requireRole(ROLE_ADMIN);

        $userDAO = new UserDAO();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
            $id        = (int)($_POST['id'] ?? 0);
            $currentId = (int)($_SESSION['user']->id ?? 0);

            // Un administrateur ne peut pas supprimer son propre compte.
            if ($id > 0 && $id !== $currentId) {
                $metier = $userDAO->getUsersById($id);
                $metier->deleteUser();
            }

            header('Location: ' . BaseURL::getBaseUrl() . 'parametres/utilisateurs');
            exit;
        }

        $users     = $userDAO->getAll();
        // Transmis à la vue pour masquer le bouton de suppression sur la ligne de l'admin connecté.
        $currentId = (int)($_SESSION['user']->id ?? 0);

        Vue::setTitle('Gestion des utilisateurs');
        Vue::render('admin/Utilisateurs', [
            'users'     => $users,
            'currentId' => $currentId,
        ]);
    }
}
