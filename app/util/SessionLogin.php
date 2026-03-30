<?php

namespace app\util;

class SessionLogin {

	private static $sessionKey = 'LOGIN';

	public static function login(): void {
		$_SESSION[self::$sessionKey] = true;
	}

	public static function logout(): void {
		unset($_SESSION[self::$sessionKey]);
	}

	public static function isLogin(): bool {
		return isset($_SESSION[self::$sessionKey]);
	}
}
