<?php

namespace app;

use app\route\Routing as Router;
use app\util\Helper as Util;

/**
 *	SETUP
 */

class Setup {
	
	public static function run(): void {
		/*** PHP 8.1+ ***/
		self::phpVers();
		
		/*** DÉPENDANCES ***/
		include 'const.php'; //Constantes
		require 'functions.php'; // Fonctions additionnelles
		require ROOT . 'vendor/autoload.php'; //Composer : autoload
		
		/*** SESSIONS ***/
		//Génération du nom pour le fichier JS géré dans : controleur/util/CustomJS.php
		if(!isset($_SESSION['CUSTOM_JS'])){ $_SESSION['CUSTOM_JS'] = Util::randNum() . '.js'; }
		
		/*** ROUTER ***/
		// Router::help(); //affiche l'index du routing
		Router::setup();
	}
	
	private static function phpVers(): void {
		if ( version_compare(PHP_VERSION, '8.1.0', '<') ) {
			die('PHP 8.1 ou supérieur est requis pour exécuter cette application !');
		}
	}
	
}
