<?php

namespace app\util;

class SessionLogin {

	private static $sessionKey = 'LOGIN';
	private static $roleKey    = 'ROLE';

	public static function login(): void {
		$_SESSION[self::$sessionKey] = true;
	}

	public static function loginWithRole(int $codeRole): void {
		$_SESSION[self::$sessionKey] = true;
		$_SESSION[self::$roleKey]    = $codeRole;
	}

	public static function logout(): void {
		unset($_SESSION[self::$sessionKey], $_SESSION[self::$roleKey]);
	}

	public static function isLogin(): bool {
		return isset($_SESSION[self::$sessionKey]);
	}

	public static function getRole(): int {
		return $_SESSION[self::$roleKey] ?? ROLE_INVITE;
	}

	// Fonction a mettre au debut du constructeur dans les controleurs
	// pour vérifier si la personne est bien login 
	// et éviter quelle modifie juste l'url et bypass le login
	// pour l'utiliser : SessionLogin::auth();
	public static function auth(): void {
		if(!self::isLogin()){
			header("Location: login");
			exit;
		}
	}
}
