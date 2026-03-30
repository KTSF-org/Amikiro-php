<?php

namespace controleur;
use app\util\BaseURL;
use vue\base\MainTemplate as Vue;

class NotFound {

	public function __construct() {

		// echo "Erreur 404 : page non trouvée !";

		/** 
		 *	Affichage (VUE) sans la classe template
		 */
		// $target = '/' . BaseURL::convertUri();
		// $actual_link = BaseURL::getBaseUrl();
		// include(MAIN_TEMPLATE_DIR . '404.php');

		/**
		 *	VUE : Méthode test() :
		 *	Affiche les chemins utilisés pour la vue et arrête l'application
		 */
		// Vue::test();

		/**
		 *	VUE : Méthode render()
		 *	Affichage (VUE) avec la classe MainTemplate (vue/base/MainTemplate.php)
		 *	1/ Nom du fichier sans .php contenu dans le répertoire : vue
		 *	2/ Option : paramètres passés dans un tableau []
		 *	3/ Option : Chemin absolu vers le fichier .php (la vue)
		 *	4/ Option : n’inclus pas le header et le footer
		 */
		Vue::render(
			'404',
			[
				'target' => '/' . BaseURL::convertUri()
			],
			MAIN_TEMPLATE_DIR,
			false
		);
		
	}
}
