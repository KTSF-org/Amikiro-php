<?php

namespace app\util;

class SessionLogin {

	private static $sessionKey = 'LOGIN';
	private static $roleKey    = 'ROLE';
	private static $idKey 	   = 'ID';

	public static function login(): void {
		$_SESSION[self::$sessionKey] = true;
	}

	public static function loginWithRole(int $codeRole, int $idKey): void {
		// Génère un nouvel ID de session pour prévenir la fixation de session.
		// L'ancien ID est invalidé côté serveur (true = delete old session).
		session_regenerate_id(true);
		$_SESSION[self::$sessionKey] = true;
		$_SESSION[self::$roleKey]    = $codeRole;
		$_SESSION[self::$idKey]      = $idKey;
	}

	public static function logout(): void {
		unset(
			$_SESSION[self::$sessionKey],
			$_SESSION[self::$roleKey],
			$_SESSION[self::$idKey],
			$_SESSION['in_live'],
			$_SESSION['live_started_at']
		);
	}

	public static function isLogin(): bool {
		return isset($_SESSION[self::$sessionKey]);
	}

	public static function getRole(): int {
		return $_SESSION[self::$roleKey] ?? ROLE_INVITE;
	}

	public static function getUserId(): int {
		return $_SESSION[self::$idKey] ?? 0;
	}

	
}
