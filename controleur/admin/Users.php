<?php

namespace controleur\admin;

use vue\base\MainTemplate as Vue;
use app\util\Guard;
use app\util\BaseURL;
use app\util\Request;
use app\util\Mailer;
use app\util\SessionLogin;
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
                $currentId = SessionLogin::getUserId();
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
        $currentId   = SessionLogin::getUserId();
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
     * - ROLE_INVITE     : adhésion auto-calculée (today + guestDefaultAccessDays).
     * - ROLE_ADHERENT   : période d'adhésion obligatoire.
     * - ROLE_NATURALISTE: période d'adhésion facultative, purement informative.
     * Un email de bienvenue avec les identifiants est envoyé après création.
     */
    private function create(): void {
        $error     = null;
        $configDAO = new ConfigDAO();
        $config    = $configDAO->getConfig();
        $guestDefaultAccessDays = (int)($config->guestDefaultAccessDays ?? 7);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name     = Request::post('name');
            $surname  = Request::post('surname');
            $mail     = Request::post('mail');
            $codeRole = (int)($_POST['codeRole'] ?? ROLE_ADHERENT);
            // Seul ROLE_ADHERENT reçoit un numéro à la création.
            // ROLE_NATURALISTE n'en a pas par défaut ; il peut en hériter s'il est promu depuis adhérent.
            $memberNum = ($codeRole === ROLE_ADHERENT)
                ? 'AMI-' . strtoupper(bin2hex(random_bytes(4)))
                : '';
            $chars    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $password = implode('', array_map(fn() => $chars[random_int(0, strlen($chars) - 1)], range(1, 8)));

            // ROLE_INVITE : durée assignée depuis Config.guestDefaultAccessDays plutôt que saisie manuellement,
            // pour rester cohérent avec la rétrogradation automatique adhérent → invité au login.
            if ($codeRole === ROLE_INVITE) {
                $startDate = date('Y-m-d');
                $endDate   = date('Y-m-d', strtotime("+{$guestDefaultAccessDays} days"));
                $hasDates  = true;
            } else {
                $startDate = $_POST['startDate'] ?? '';
                $endDate   = $_POST['endDate']   ?? '';
                $hasDates  = !empty($startDate) || !empty($endDate);
            }

            $userDAO = new UserDAO();

            if ($codeRole === ROLE_ADMIN) {
                $error = 'Impossible de créer un compte administrateur.';
            } elseif (empty($name) || empty($surname) || empty($mail)) {
                $error = 'Tous les champs obligatoires doivent être remplis.';
            } elseif ($userDAO->getUserByEmail($mail)) {
                $error = 'Cette adresse email est déjà utilisée.';
            } elseif ($codeRole === ROLE_ADHERENT && !$hasDates) {
                $error = 'La période d\'adhésion est obligatoire pour un adhérent.';
            } elseif ($hasDates && (empty($startDate) || empty($endDate))) {
                $error = 'Les deux dates de l\'adhésion sont obligatoires.';
            } elseif ($hasDates && $endDate <= $startDate) {
                $error = 'La date de fin doit être postérieure à la date de début.';
            } else {
                // 'tmp' est un placeholder : setPassword() ci-dessous l'écrase immédiatement avec le hash bcrypt.
                $user = new User($codeRole, $mail, 0, 'tmp', $name, $surname, 0, $memberNum);
                $user->setPassword($password);
                if ($userDAO->create($user)) {
                    if ($hasDates) {
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
        Vue::render('admin/UsersCreate', [
            'error'                  => $error,
            'guestDefaultAccessDays' => $guestDefaultAccessDays,
        ]);
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
        $currentId           = SessionLogin::getUserId();
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
                    $isPromotion = $user->getCodeRole() === ROLE_INVITE && !empty($_POST['promoteToAdherent']);

                    // À la promotion, l'historique invité est effacé volontairement :
                    // les périodes d'accès invité n'ont pas de valeur une fois le compte passé adhérent.
                    if ($isPromotion) {
                        $subscriptionDAO->deleteByUser($id);
                    }

                    $subscriptionSuccess = $subscriptionDAO->createForUser($id, $startDate, $endDate);

                    if ($subscriptionSuccess && $isPromotion) {
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

                // La rétrogradation manuelle vers ROLE_INVITE est bloquée :
                // elle ne peut se produire qu'automatiquement au login quand l'adhésion expire.
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
