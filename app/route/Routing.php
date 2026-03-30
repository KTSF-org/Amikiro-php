<?php

namespace app\route;

use app\route\base\Router;

/*
 *	-- ROUTING --
 *	L'ensemble des routes déclarées dans cette classe sont accessibles depuis :
 *	http://localhost/apptest/nom_de_la_route
 *	-- OU --
 *	http://localhost/apptest/cible/de/la/route
 */

class Routing {

	public static bool $debug = false;

	public static function setup():void {

		$route = new Router();

		//PAGE LOGIN
		$route->add('', 'controleur\Login'); //page par défaut
		$route->add('/login', 'controleur\Login');
		$route->add('/accueil', 'controleur\Accueil');
		$route->add('/about', 'controleur\About');
		//charge une image en interne (hors asset) :
		$route->add('/img', 'controleur\util\Image');
		//charge la classe MainAjax($message), 'Hello AJAX' un message de sortie par défaut :
		$route->add('/ajax', 'controleur\MainAjax', 'Hello AJAX');
		//si l'on souhaite passer plusieurs paramètres, il faut ajouter un tableau :
		$route->add('/admin/test', 'controleur\admin\Test', ['hello', 'world']);
		//méthode / fonction personalisée :
		$route->add('/phpinfo', function () { phpinfo(); });
		//ajout d'un controleur JavaScript (génération dynamique d'un script JS), voir app/Setup.php :
		$route->add('asset/js/' . $_SESSION['CUSTOM_JS'], 'controleur\util\CustomJS');

        $route->add('/live', 'controleur\Live');
		
		


		//Contrôleur 404 par défaut :
		$route->set404('controleur\NotFound');
		
		if (self::$debug) $route->help();
		$route->run();
	}

	public static function help():void {
		self::$debug = true;
	}
}
