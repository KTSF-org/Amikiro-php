<?php

namespace controleur\util;
use app\util\Helper as Util;

/** 
 *	Cette classe construit un javascript à partir des données PHP.
 *	Principalement utilisée afin de charger les paramètres de l'application,
 *	et donc de l'appliquer le front (en JS). Pour l'exemple, voir le fichier :
 *	app/param.php => AJAX_DEBUG
 *	Le constructeur accessible depuis la session : $_SESSION['CUSTOM_JS'] dans :
 *	app/setup.php
 *	app/route/routing.php
 */

class CustomJS {

	public function __construct() {
		
		$js = "/** CUSTOM JS **/" . PHP_EOL . PHP_EOL;
		
		//--- mainUrl ---
		// Base URL de l'application
		// -> const mainUrl="http://locahost/apptest/";
		$js .= 'const mainUrl = "' . \app\util\BaseURL::getBaseUrl() . '";' . PHP_EOL;
		
		//--- ajaxDebug ---
		// affiche en JS : 
		// -> const ajaxDebug = true; 
		// -> const ajaxDebug = false;
		$js .= 'const ajaxDebug = ' . Util::convertBoolStr(AJAX_DEBUG) . ';' . PHP_EOL;
		
		header('Content-Type: application/javascript');
		
		echo $js;
	}

}
