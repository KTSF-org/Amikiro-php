<?php

namespace controleur;

use vue\base\MainTemplate as Vue;
class Accueil {

	public function __construct() {

		/**
		 *	    SESSION
		 */

		$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
		
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
