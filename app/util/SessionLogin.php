<?php

namespace app\util;

class SessionLogin {

	private static $sessionKey = 'LOGIN';
	private static $roleKey    = 'ROLE';

	public static function login(): void {
		$_SESSION[self::$sessionKey] = true;
	}

	public static function loginWithRole($user, int $codeRole): void {
		// Evite de stocker le mdp dans la session
		if(isset($user->password)){
			unset($user->password);
		}

		$_SESSION[self::$sessionKey] = $user;
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

	
}
