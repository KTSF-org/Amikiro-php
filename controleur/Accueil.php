<?php

namespace controleur;

use modele\DAO\UserDAO;
use modele\User;
use vue\base\MainTemplate as Vue;
use app\util\SessionLogin as UserSession;
class Accueil {

	public function __construct() {

		/**
		 *	    SESSION
		 */
		
		
		$userId = UserSession::getUserId();
		$userDAO = new UserDAO();
		$user = $userDAO->getUsersById($userId);
		

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
