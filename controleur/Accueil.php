<?php

namespace controleur;

use modele\DAO\UserDAO;
use modele\User;
use vue\base\MainTemplate as Vue;
use app\util\SessionLogin as UserSession;
use app\util\UserInfo;
class Accueil {

	public function __construct() {

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
