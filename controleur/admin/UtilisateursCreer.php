<?php

namespace controleur\admin;

use vue\base\MainTemplate as Vue;
use app\util\Guard;
use app\util\BaseURL;
use app\util\Request;
use modele\User;
use modele\DAO\UserDAO;

/**
 * CONTRÔLEUR : Admin / UtilisateursCreer
 * Gère la création d'un nouveau compte utilisateur.
 * Accès restreint à ROLE_ADMIN.
 *
 * Sur POST : valide les champs, construit l'objet User, hache le mot de passe
 *   via setPassword(), insère en base, puis redirige vers la liste.
 * Sur GET  : affiche le formulaire vide.
 *
 * Règles métier appliquées :
 *   - ROLE_ADMIN non assignable à la création (bloqué côté serveur).
 *   - Les champs prénom, nom, email et mot de passe sont obligatoires.
 */
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
                // Le constructeur User exige un password non vide : 'tmp' est un placeholder
                // immédiatement remplacé par le hash de $password via setPassword().
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
