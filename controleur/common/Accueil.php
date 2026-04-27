<?php

namespace controleur\common;

use modele\DAO\UserDAO;
use vue\base\MainTemplate as Vue;
use app\util\SessionLogin as UserSession;
use app\util\Guard;

class Accueil {

	public function __construct() {
		Guard::requireLogin();

		/**
		 *	    SESSION
		 */
		
		$id = UserSession::getUserId();
		$userDAO = new UserDAO();
		// READ
		$user = $userDAO->getUsersById($id);

		// getter
		$surname = $user->getSurname();
		$name = $user->getName();
		
		
		
		

		/**
		 *	    VUES
		 */
		

		Vue::setTitle('Accueil');

		Vue::addCSS([
			ASSET . '/css/accueil.css',
		]);

		Vue::render('common/Accueil', [
			'surname' => $surname,
			'name' => $name,
		]);

	}
}
