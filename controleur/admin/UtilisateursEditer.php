<?php

namespace controleur\admin;

use vue\base\MainTemplate as Vue;
use app\util\Guard;
use app\util\BaseURL;
use app\util\Request;
use modele\DAO\UserDAO;
use modele\DAO\AbonnementDAO;

class UtilisateursEditer {

    public function __construct() {
        Guard::requireRole(ROLE_ADMIN);

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ' . BaseURL::getBaseUrl() . 'parametres/utilisateurs');
            exit;
        }

        $userDAO        = new UserDAO();
        $abonnementDAO  = new AbonnementDAO();
        $metier         = $userDAO->getUsersById($id);
        $currentId      = (int)($_SESSION['user']->id ?? 0);
        $isSelf         = ($id === $currentId);
        $error          = null;
        $errorAbo       = null;
        $successAbo     = null;

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
                    // Promotion ROLE_INVITE → ROLE_ADHERENT uniquement
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
        }

        $abonnementActif    = $abonnementDAO->getActiveByUser($id);
        $historiqueAbo      = $abonnementDAO->getAllByUser($id);

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
