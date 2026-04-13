<?php

namespace controleur\admin;

use vue\base\MainTemplate as Vue;
use app\util\Guard;
use app\util\BaseURL;
use app\util\Request;
use modele\User;
use modele\DAO\UserDAO;
use modele\DAO\AbonnementDAO;

class Utilisateurs {

    public function __construct() {
        Guard::requireRole(ROLE_ADMIN);

        match ($_GET['page'] ?? '') {
            'creer'  => $this->creer(),
            'editer' => $this->editer(),
            default  => $this->liste(),
        };
    }

    private function liste(): void {
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

    private function creer(): void {
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

    private function editer(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ' . BaseURL::getBaseUrl() . 'parametres/utilisateurs');
            exit;
        }

        $userDAO       = new UserDAO();
        $abonnementDAO = new AbonnementDAO();
        $metier        = $userDAO->getUsersById($id);
        $currentId     = (int)($_SESSION['user']->id ?? 0);
        $isSelf        = ($id === $currentId);
        $error         = null;
        $errorAbo      = null;
        $successAbo    = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'identity';

            if ($action === 'abonnement') {
                $startDate = $_POST['startDate'] ?? '';
                $endDate   = $_POST['endDate']   ?? '';

                if (empty($startDate) || empty($endDate)) {
                    $errorAbo = 'Les deux dates sont obligatoires.';
                } elseif ($endDate <= $startDate) {
                    $errorAbo = 'La date de fin doit être postérieure à la date de début.';
                } else {
                    $successAbo = $abonnementDAO->createForUser($id, $startDate, $endDate);

                    if ($successAbo && $metier->getCodeRole() === ROLE_INVITE) {
                        $metier->setCodeRole(ROLE_ADHERENT);
                        $userDAO->update($metier);
                    }
                }

            } else {
                $name      = Request::post('name');
                $surname   = Request::post('surname');
                $mail      = Request::post('mail');
                $password  = Request::post('password');
                $memberNum = (int)($_POST['memberNum'] ?? $metier->getMemberNum());
                $codeRole  = $isSelf
                    ? $metier->getCodeRole()
                    : (int)($_POST['codeRole'] ?? $metier->getCodeRole());

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
        }

        $abonnementActif = $abonnementDAO->getActiveByUser($id);
        $historiqueAbo   = $abonnementDAO->getAllByUser($id);

        Vue::setTitle('Modifier un compte');
        Vue::render('admin/UtilisateursEditer', [
            'metier'          => $metier,
            'error'           => $error,
            'isSelf'          => $isSelf,
            'abonnementActif' => $abonnementActif,
            'historiqueAbo'   => $historiqueAbo,
            'errorAbo'        => $errorAbo,
            'successAbo'      => $successAbo,
        ]);
    }
}
