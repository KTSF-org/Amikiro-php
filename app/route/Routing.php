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

		// Journal et ses sous-pages (édition des données métier)
		$route->add('/journal', 'controleur\Journal');
		$route->add('/journal/categorie', 'controleur\journal\Categorie');
		$route->add('/journal/fiche', 'controleur\journal\Fiche');

		// Déconnexion — détruit la session et redirige vers /login
		$route->add('/logout', 'controleur\Logout');

		$route->add('/sectionBat', 'controleur\SectionBat');
		$route->add('/sectionColony', 'controleur\SectionColony');

		// Paramètres utilisateur
		$route->add('/parametres', 'controleur\Parametres');
		$route->add('/parametres/profil', 'controleur\Profil');

		// Paramètres admin (accès restreint à ROLE_ADMIN, contrôle à implémenter dans chaque contrôleur)
		$route->add('/parametres/utilisateurs', 'controleur\admin\Utilisateurs');
		$route->add('/parametres/webcam', 'controleur\admin\Webcam');

		$route->add('/confidentialite', 'controleur\Confidentialite');
		
		


		//Contrôleur 404 par défaut :
		$route->set404('controleur\NotFound');
		
		if (self::$debug) $route->help();
		$route->run();
	}

	public static function help():void {
		self::$debug = true;
	}
}
