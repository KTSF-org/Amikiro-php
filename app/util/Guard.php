<?php

namespace app\util;

/**
 * UTILITAIRE : Guard
 * Vérification des droits d'accès en entrée de contrôleur.
 * Redirige silencieusement si les conditions ne sont pas remplies.
 *
 * Utilisation :
 *   Guard::requireLogin()           — page accessible à tout utilisateur connecté
 *   Guard::requireRole(ROLE_ADMIN)  — page restreinte à un rôle minimum
 */
class Guard {

    /**
     * Redirige vers /login si l'utilisateur n'est pas authentifié.
     */
    public static function requireLogin(): void {
        if (DEV_MODE) {
            self::injectDevSession();
            return;
        }
        if (!SessionLogin::isLogin()) {
            header('Location: ' . BaseURL::getBaseUrl() . 'login');
            exit;
        }
    }

    /**
     * Vérifie l'authentification puis le rôle minimum requis.
     * Redirige vers /accueil si le rôle est insuffisant.
     *
     * @param int $role Rôle minimum requis (ex: ROLE_ADMIN)
     */
    public static function requireRole(int $role): void {
        if (DEV_MODE) {
            self::injectDevSession();
            return;
        }
        self::requireLogin();
        if (SessionLogin::getRole() < $role) {
            header('Location: ' . BaseURL::getBaseUrl() . 'accueil');
            exit;
        }
    }

    /**
     * Injecte une session ROLE_ADMIN fictive pour le développement.
     * N'écrase pas une session existante.
     */
    private static function injectDevSession(): void {
        if (!SessionLogin::isLogin()) {
            $dev = new \stdClass();
            $dev->id        = 0;
            $dev->name      = 'Dev';
            $dev->surname   = 'Admin';
            $dev->mail      = 'dev@local';
            $dev->codeRole  = ROLE_ADMIN;
            $_SESSION['user'] = $dev;
            SessionLogin::loginWithRole(ROLE_ADMIN, 0);
        }
    }
}
