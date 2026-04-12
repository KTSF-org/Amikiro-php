<?php

namespace controleur;

use modele\DAO\UserDAO;
use modele\User;
use vue\base\MainTemplate as Vue;
use app\util\SessionLogin as UserSession;
use app\util\UserInfo;
use app\util\Guard;

class Accueil {

	public function __construct() {
		Guard::requireLogin();

		/**
		 *	    SESSION
		 */
		
		
		$user = UserInfo::getUserInfo();

		/**
		 *	    VUES
		 */
		

		Vue::setTitle('Accueil');

		Vue::addCSS([
			ASSET . '/css/accueil.css',
		]);

		Vue::render('Accueil', [
			'user' => $user,
		]);

	}
}
