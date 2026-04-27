<?php

namespace controleur\admin;

use vue\base\MainTemplate as Vue;
use app\util\Guard;
use app\util\BaseURL;
use app\util\Request;
use app\util\Mailer;
use modele\User;
use modele\DAO\UserDAO;
use modele\DAO\SubscriptionDAO;

class Users {

    public function __construct() {
        Guard::requireRole(ROLE_ADMIN);

        match ($_GET['page'] ?? '') {
            'create' => $this->create(),
            'edit'   => $this->edit(),
            default  => $this->index(),
        };
    }

    private function index(): void {
        $userDAO = new UserDAO();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
            $id        = (int)($_POST['id'] ?? 0);
            $currentId = (int)($_SESSION['user']->id ?? 0);

            if ($id > 0 && $id !== $currentId) {
                $user = $userDAO->getUsersById($id);
                $user->deleteUser();
            }

            header('Location: ' . BaseURL::getBaseUrl() . 'parametres/utilisateurs');
            exit;
        }

        $users     = $userDAO->getAll();
        $currentId = (int)($_SESSION['user']->id ?? 0);

        Vue::setTitle('Gestion des utilisateurs');
        Vue::render('admin/Users', [
            'users'     => $users,
            'currentId' => $currentId,
        ]);
    }

    private function create(): void {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name      = Request::post('name');
            $surname   = Request::post('surname');
            $mail      = Request::post('mail');
            $codeRole  = (int)($_POST['codeRole'] ?? ROLE_ADHERENT);
            $memberNum = 'AMI-' . strtoupper(bin2hex(random_bytes(4)));
            $startDate = $_POST['startDate'] ?? '';
            $endDate   = $_POST['endDate']   ?? '';
            $hasDates  = !empty($startDate) || !empty($endDate);
            $chars     = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $password  = implode('', array_map(fn() => $chars[random_int(0, strlen($chars) - 1)], range(1, 8)));

            if ($codeRole === ROLE_ADMIN) {
                $error = 'Impossible de créer un compte administrateur.';
            } elseif (empty($name) || empty($surname) || empty($mail)) {
                $error = 'Tous les champs obligatoires doivent être remplis.';
            } elseif ($codeRole === ROLE_ADHERENT && $hasDates && (empty($startDate) || empty($endDate))) {
                $error = 'Les deux dates de la période d\'accès sont obligatoires.';
            } elseif ($codeRole === ROLE_ADHERENT && $hasDates && $endDate <= $startDate) {
                $error = 'La date de fin doit être postérieure à la date de début.';
            } else {
                $user = new User($codeRole, $mail, 0, 'tmp', $name, $surname, 0, $memberNum);
                $user->setPassword($password);

                $userDAO = new UserDAO();
                if ($userDAO->create($user)) {
                    if ($codeRole === ROLE_ADHERENT && !empty($startDate) && !empty($endDate)) {
                        $subscriptionDAO = new SubscriptionDAO();
                        $subscriptionDAO->createForUser($user->getId(), $startDate, $endDate);
                    }
                    Mailer::sendWelcome($mail, $name, $password, $memberNum);
                    header('Location: ' . BaseURL::getBaseUrl() . 'parametres/utilisateurs');
                    exit;
                }
                $error = 'Erreur lors de la création du compte.';
            }
        }

        Vue::setTitle('Créer un compte');
        Vue::render('admin/UsersCreate', ['error' => $error]);
    }

    private function edit(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ' . BaseURL::getBaseUrl() . 'parametres/utilisateurs');
            exit;
        }

        $userDAO             = new UserDAO();
        $subscriptionDAO     = new SubscriptionDAO();
        $user                = $userDAO->getUsersById($id);
        $currentId           = (int)($_SESSION['user']->id ?? 0);
        $isSelf              = ($id === $currentId);
        $error               = null;
        $subscriptionError   = null;
        $subscriptionSuccess = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'identity';

            if ($action === 'subscription') {
                $startDate = $_POST['startDate'] ?? '';
                $endDate   = $_POST['endDate']   ?? '';

                if (empty($startDate) || empty($endDate)) {
                    $subscriptionError = 'Les deux dates sont obligatoires.';
                } elseif ($endDate <= $startDate) {
                    $subscriptionError = 'La date de fin doit être postérieure à la date de début.';
                } else {
                    $subscriptionSuccess = $subscriptionDAO->createForUser($id, $startDate, $endDate);

                    if ($subscriptionSuccess && $user->getCodeRole() === ROLE_INVITE) {
                        $user->setCodeRole(ROLE_ADHERENT);
                        $userDAO->update($user);
                    }
                }

            } else {
                $name      = Request::post('name');
                $surname   = Request::post('surname');
                $mail      = Request::post('mail');
                $password  = Request::post('password');
                $memberNum = $_POST['memberNum'] ?? $user->getMemberNum();
                $codeRole  = $isSelf
                    ? $user->getCodeRole()
                    : (int)($_POST['codeRole'] ?? $user->getCodeRole());

                if (!$isSelf && $codeRole === ROLE_ADMIN) {
                    $error = 'Impossible d\'attribuer le rôle administrateur.';
                } elseif (empty($name) || empty($surname) || empty($mail)) {
                    $error = 'Tous les champs obligatoires doivent être remplis.';
                } else {
                    $user->setName($name)
                         ->setSurname($surname)
                         ->setMail($mail)
                         ->setCodeRole($codeRole)
                         ->setMemberNum($memberNum);

                    if (!empty($password)) {
                        $user->setPassword($password);
                    }

                    if ($userDAO->update($user)) {
                        header('Location: ' . BaseURL::getBaseUrl() . 'parametres/utilisateurs');
                        exit;
                    }
                    $error = 'Erreur lors de la mise à jour du compte.';
                }
            }
        }

        $activeSubscription  = $subscriptionDAO->getActiveByUser($id);
        $subscriptionHistory = $subscriptionDAO->getAllByUser($id);

        Vue::setTitle('Modifier un compte');
        Vue::render('admin/UsersEdit', [
            'user'                => $user,
            'error'               => $error,
            'isSelf'              => $isSelf,
            'activeSubscription'  => $activeSubscription,
            'subscriptionHistory' => $subscriptionHistory,
            'subscriptionError'   => $subscriptionError,
            'subscriptionSuccess' => $subscriptionSuccess,
        ]);
    }
}
