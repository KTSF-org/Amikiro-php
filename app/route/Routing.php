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
		$route->add('', 'controleur\common\Login'); //page par défaut
		$route->add('/login', 'controleur\common\Login');
		$route->add('/accueil', 'controleur\common\Accueil');
		$route->add('/about', 'controleur\common\About');
		//charge une image en interne (hors asset) :
		$route->add('/img', 'controleur\util\Image');
		//charge la classe MainAjax($message), 'Hello AJAX' un message de sortie par défaut :
		$route->add('/ajax', 'controleur\MainAjax', 'Hello AJAX');
		//méthode / fonction personalisée :
		$route->add('/phpinfo', function () { phpinfo(); });
		//login automatique en dev — à retirer avant mise en production :
		$route->add('/dev/login', 'controleur\DevLogin');
		//ajout d'un controleur JavaScript (génération dynamique d'un script JS), voir app/Setup.php :
		$route->add('asset/js/' . $_SESSION['CUSTOM_JS'], 'controleur\util\CustomJS');

        $route->add('/live', 'controleur\Live');

		// Journal et ses sous-pages (édition des données métier)
		$route->add('/journal', 'controleur\journal\Journal');
		// $route->add('/journal/categorie', 'controleur\journal\Categorie');

		// Déconnexion — détruit la session et redirige vers /login
		$route->add('/logout', 'controleur\common\Logout');

		$route->add('/sectionBat', 'controleur\journal\SectionBat');
		$route->add('/sectionColony', 'controleur\journal\SectionColony');

		// Paramètres utilisateur
		$route->add('/parametres/profil', 'controleur\Profil');

		// Paramètres admin (accès restreint à ROLE_ADMIN, contrôle à implémenter dans chaque contrôleur)
		// Une seule route gère liste, création et édition via $_GET['page'] dans le contrôleur
		$route->add('/parametres/utilisateurs', 'controleur\admin\Users');
		$route->add('/parametres/webcam', 'controleur\admin\Webcam');
		// URL SPECIFIQUE ADMIN
		$route->add('/' . URL_ADMIN, 'controleur\admin\AdminControleur');

		$route->add('/confidentialite', 'controleur\legale\Confidentialite');
		$route->add('/mentionslegales', 'controleur\legale\MentionsLegales');
		
		$route->add('/captcha', 'controleur\util\Captcha');


		//Contrôleur 404 par défaut :
		$route->set404('controleur\common\NotFound');
		
		if (self::$debug) $route->help();
		$route->run();
	}

	public static function help():void {
		self::$debug = true;
	}
}
