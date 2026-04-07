<?php

namespace controleur;

use vue\base\MainTemplate as Vue;

class Accueil {

	public function __construct() {

		/**
		 *	    SESSION
		 */
		
		// On récupère l'objet qu'on a stocké dans 'LOGIN' au moment du login
		$user = $_SESSION['LOGIN'] ?? null;
		
		
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
