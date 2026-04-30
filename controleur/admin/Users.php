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
use modele\DAO\ConfigDAO;

/**
 * Contrôleur : gestion des comptes utilisateurs (admin uniquement).
 *
 * Une seule route gère trois pages via le paramètre GET ?page :
 *   - (vide)   → liste des comptes
 *   - create   → formulaire de création
 *   - edit&id  → formulaire de modification
 */
class Users {

    public function __construct() {
        Guard::requireRole(ROLE_ADMIN);

        match ($_GET['page'] ?? '') {
            'create' => $this->create(),
            'edit'   => $this->edit(),
            default  => $this->index(),
        };
    }

    /**
     * Affiche la liste de tous les comptes.
     * Gère aussi la suppression (POST action=delete).
     * Un admin ne peut pas supprimer son propre compte.
     */
    private const PER_PAGE = 20;

    private function index(): void {
        $userDAO         = new UserDAO();
        $subscriptionDAO = new SubscriptionDAO();
        $configDAO       = new ConfigDAO();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'delete') {
                $id        = (int)($_POST['id'] ?? 0);
                $currentId = (int)($_SESSION['user']->id ?? 0);
                if ($id > 0 && $id !== $currentId) {
                    // Nettoie les abonnements avant la suppression du compte
                    $subscriptionDAO->deleteByUser($id);
                    $userDAO->getUsersById($id)->deleteUser();
                }
                header('Location: ' . BaseURL::getBaseUrl() . 'parametres/utilisateurs');
                exit;
            }

            if ($action === 'saveConfig') {
                $days = max(1, (int)($_POST['guestDefaultAccessDays'] ?? 7));
                $configDAO->updateConfig(['guestDefaultAccessDays' => $days]);
                header('Location: ' . BaseURL::getBaseUrl() . 'parametres/utilisateurs');
                exit;
            }
        }

        $roleFilter  = isset($_GET['role']) && $_GET['role'] !== '' ? (int)$_GET['role'] : -1;
        $currentPage = max(1, (int)($_GET['p'] ?? 1));
        $offset      = ($currentPage - 1) * self::PER_PAGE;

        $totalUsers  = $userDAO->countFiltered($roleFilter);
        $totalPages  = max(1, (int)ceil($totalUsers / self::PER_PAGE));
        $currentPage = min($currentPage, $totalPages);
        $users       = $userDAO->getAllFiltered($roleFilter, $offset, self::PER_PAGE);
        $currentId   = (int)($_SESSION['user']->id ?? 0);
        $config      = $configDAO->getConfig();
        $guestDefaultAccessDays = (int)($config->guestDefaultAccessDays ?? 7);

        $userIds      = array_map(fn($u) => (int)$u->id, $users);
        $activeByUser = $subscriptionDAO->getActiveByUserIds($userIds);

        Vue::setTitle('Gestion des utilisateurs');
        Vue::render('admin/Users', [
            'users'                  => $users,
            'currentId'              => $currentId,
            'guestDefaultAccessDays' => $guestDefaultAccessDays,
            'activeByUser'           => $activeByUser,
            'currentPage'            => $currentPage,
            'totalPages'             => $totalPages,
            'roleFilter'             => $roleFilter,
        ]);
    }

    /**
     * Crée un nouveau compte utilisateur.
     *
     * Le mot de passe (8 caractères aléatoires) et le numéro adhérent
     * sont générés automatiquement côté serveur, jamais saisis dans le formulaire.
     * Si le rôle est ROLE_ADHERENT et que des dates sont fournies,
     * une période d'accès est créée immédiatement.
     * Un email de bienvenue avec les identifiants est envoyés après création.
     */
    private function create(): void {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name      = Request::post('name');
            $surname   = Request::post('surname');
            $mail      = Request::post('mail');
            $codeRole  = (int)($_POST['codeRole'] ?? ROLE_ADHERENT);
            // Les invités n'ont pas de numéro adhérent ; généré pour les autres rôles
            $memberNum = ($codeRole !== ROLE_INVITE)
                ? 'AMI-' . strtoupper(bin2hex(random_bytes(4)))
                : '';
            $startDate = $_POST['startDate'] ?? '';
            $endDate   = $_POST['endDate']   ?? '';
            // Vrai si l'admin a rempli au moins une des deux dates
            $hasDates  = !empty($startDate) || !empty($endDate);
            $chars     = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            // random_int est cryptographiquement sûr, contrairement à rand()
            $password  = implode('', array_map(fn() => $chars[random_int(0, strlen($chars) - 1)], range(1, 8)));

            // Les invités doivent obligatoirement avoir un temps d'accès à la création
            $inviteRequiresDates = ($codeRole === ROLE_INVITE && (empty($startDate) || empty($endDate)));

            $userDAO = new UserDAO();

            if ($codeRole === ROLE_ADMIN) {
                $error = 'Impossible de créer un compte administrateur.';
            } elseif (empty($name) || empty($surname) || empty($mail)) {
                $error = 'Tous les champs obligatoires doivent être remplis.';
            } elseif ($userDAO->getUserByEmail($mail)) {
                $error = 'Cette adresse email est déjà utilisée.';
            } elseif ($inviteRequiresDates) {
                $error = 'Un temps d\'accès est obligatoire pour un compte invité.';
            } elseif ($hasDates && (empty($startDate) || empty($endDate))) {
                $error = 'Les deux dates du temps d\'accès sont obligatoires.';
            } elseif ($hasDates && $endDate <= $startDate) {
                $error = 'La date de fin doit être postérieure à la date de début.';
            } else {
                $user = new User($codeRole, $mail, 0, 'tmp', $name, $surname, 0, $memberNum);
                $user->setPassword($password);
                if ($userDAO->create($user)) {
                    // Création du temps d'accès si des dates sont fournies (obligatoire pour invité)
                    if (!empty($startDate) && !empty($endDate)) {
                        $subscriptionDAO = new SubscriptionDAO();
                        $subscriptionDAO->createForUser($user->getId(), $startDate, $endDate);
                    }
                    // L'échec du mail ne bloque pas la création du compte
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

    /**
     * Modifie un compte existant.
     *
     * Deux formulaires distincts sur la même page, identifiés par POST action :
     *   - identity => mise à jour des informations du compte
     *   - subscription => ajout d'une nouvelle période d'accès
     *
     * Un admin ne peut pas modifier son propre rôle.
     * L'ajout d'une période d'accès à un ROLE_INVITE le promeut automatiquement
     * en ROLE_ADHERENT.
     */
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

                    // Promotion explicite en adhérent si la case est cochée et que l'utilisateur est invité
                    if ($subscriptionSuccess && $user->getCodeRole() === ROLE_INVITE
                        && !empty($_POST['promoteToAdherent'])) {
                        // Restitue l'ancien numéro s'il existe, sinon en génère un nouveau
                        if (empty($user->getMemberNum())) {
                            $user->setMemberNum('AMI-' . strtoupper(bin2hex(random_bytes(4))));
                        }
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

                $isDowngradeToInvite = $codeRole === ROLE_INVITE
                    && in_array($user->getCodeRole(), [ROLE_ADHERENT, ROLE_NATURALISTE]);

                $existingMail = $userDAO->getUserByEmail($mail);
                $mailTaken    = $existingMail && (int)$existingMail->id !== $id;

                if (!$isSelf && $codeRole === ROLE_ADMIN) {
                    $error = 'Impossible d\'attribuer le rôle administrateur.';
                } elseif (!$isSelf && $isDowngradeToInvite) {
                    $error = 'Un adhérent ou naturaliste ne peut pas être rétrogradé manuellement en invité.';
                } elseif (empty($name) || empty($surname) || empty($mail)) {
                    $error = 'Tous les champs obligatoires doivent être remplis.';
                } elseif ($mailTaken) {
                    $error = 'Cette adresse email est déjà utilisée par un autre compte.';
                } else {
                    // Génère un memberNum si l'utilisateur devient adhérent sans en avoir un
                    if ($codeRole === ROLE_ADHERENT && empty($memberNum)) {
                        $memberNum = 'AMI-' . strtoupper(bin2hex(random_bytes(4)));
                    }

                    $user->setName($name)
                         ->setSurname($surname)
                         ->setMail($mail)
                         ->setCodeRole($codeRole)
                         ->setMemberNum($memberNum);

                    // Le mot de passe n'est mis à jour que s'il est fourni
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
