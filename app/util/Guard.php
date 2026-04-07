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
        self::requireLogin();
        if (SessionLogin::getRole() < $role) {
            header('Location: ' . BaseURL::getBaseUrl() . 'accueil');
            exit;
        }
    }
}
