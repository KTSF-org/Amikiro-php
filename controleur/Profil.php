<?php

namespace controleur;

use app\util\Request;
use app\util\Guard;
use app\util\BaseURL;
use app\util\SessionLogin as UserSession;
use vue\base\MainTemplate as Vue;
use modele\User;
use modele\DAO\UserDAO;
use modele\DAO\SubscriptionDAO;

/**
 * Contrôleur : profil de l'utilisateur connecté.
 * Permet de modifier le prénom/nom et le mot de passe.
 *
 * Deux actions POST dispatchées via $_POST['action'] :
 *   identity — met à jour name et surname (email non modifiable par l'utilisateur)
 *   password — change le mot de passe après vérification de l'actuel
 */
class Profil {

    public function __construct() {
        Guard::requireLogin();
        $this->handle();
    }

    private function handle(): void {
        $userDAO = new UserDAO();
        // getUsersById() retourne un objet User avec propriétés privées — utiliser les getters
        $user    = $userDAO->getUsersById(UserSession::getUserId());
        $role    = UserSession::getRole();

        $success = null;
        $error   = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Les setters appelés dans les méthodes modifient $user en place :
            // $user->getName() reflétera les nouvelles valeurs au moment du rendu.
            ['success' => $success, 'error' => $error] = match ($_POST['action'] ?? '') {
                'identity'      => $this->handleIdentityUpdate($user, $userDAO),
                'password'      => $this->handlePasswordChange($user, $userDAO),
                'deleteAccount' => $this->deleteAccount($user),
                default         => ['success' => null, 'error' => null],
            };
        }

        $activeSub = (new SubscriptionDAO())->getActiveByUser($user->getId());

        Vue::setTitle('Mon Profil');
        Vue::addJS([ASSET . '/js/profil.js']);
        Vue::render('Profil', [
            'surname'      => $user->getSurname(),
            'name'         => $user->getName(),
            'role'         => $role,
            'mail'         => $user->getMail(),
            'memberNum'    => $user->getMemberNum(),
            'hasActiveSub' => (bool) $activeSub,
            'success'      => $success,
            'error'        => $error,
        ]);
    }

    /**
     * Met à jour le prénom et le nom de l'utilisateur.
     * L'email n'est pas modifiable par l'utilisateur lui-même.
     * @return array{success: bool|null, error: string|null}
     */
    private function handleIdentityUpdate(User $user, UserDAO $userDAO): array {
        $name    = Request::post('name');
        $surname = Request::post('surname');

        if (empty($name) || empty($surname)) {
            return ['success' => null, 'error' => 'Le prénom et le nom ne peuvent pas être vides.'];
        }

        $user->setName($name)->setSurname($surname);
        return ['success' => $userDAO->update($user), 'error' => null];
    }

    /**
     * Change le mot de passe après vérification de l'actuel.
     * password_verify() est appelé ici car User n'expose pas de méthode de vérification —
     * il ne stocke que le hash et délègue le contrôle à l'appelant.
     * @return array{success: bool|null, error: string|null}
     */
    private function handlePasswordChange(User $user, UserDAO $userDAO): array {
        $current = Request::post('current_password');
        $new     = Request::post('new_password');
        $confirm = Request::post('confirm_password');

        // Vérification de l'actuel avant d'accepter le changement —
        // évite qu'une session volée puisse changer le mot de passe sans connaître l'original.
        if (!password_verify($current, $user->getPassword())) {
            return ['success' => null, 'error' => 'Mot de passe actuel incorrect.'];
        }
        if (empty($new)) {
            return ['success' => null, 'error' => 'Le nouveau mot de passe ne peut pas être vide.'];
        }
        if ($new !== $confirm) {
            return ['success' => null, 'error' => 'Les mots de passe ne correspondent pas.'];
        }

        // setPassword() hache automatiquement via password_hash() (bcrypt cost 12)
        $user->setPassword($new);
        return ['success' => $userDAO->update($user), 'error' => null];
    }

    /**
     * Supprime le compte de l'invité connecté après vérification du mot de passe.
     * Réservé aux ROLE_INVITE — les autres rôles sont ignorés.
     * Redirige vers /login après suppression.
     * @return array{success: bool|null, error: string|null}
     */
    private function deleteAccount(User $user): array {
        if (UserSession::getRole() !== ROLE_INVITE) {
            return ['success' => null, 'error' => null];
        }

        $password = Request::post('confirm_delete_password');

        if (!password_verify($password, $user->getPassword())) {
            return ['success' => null, 'error' => 'Mot de passe incorrect — suppression annulée.'];
        }

        $id = $user->getId();
        (new SubscriptionDAO())->deleteByUser($id);
        (new UserDAO())->getUsersById($id)->deleteUser();

        UserSession::logout();
        header('Location: ' . BaseURL::getBaseUrl() . 'login');
        exit;
    }
}
