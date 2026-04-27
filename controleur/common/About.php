<?php

namespace controleur\common;

use app\util\Request as req;
use vue\base\MainTemplate as Vue;

class About {

	public function __construct() {

		//Utilisation de la classe Request (app/util/Request.php)
		$version = req::get('app', 0);

		$test = false;
		if($version === 'post_test' && req::is('aboutPost') ) {
			$test = req::post('aboutPost', 'aucune donnée reçue');
		}

		//Lesture du fichier texte et conversion HTML :
		$readme = ROOT . 'readme.txt';
		file_exists($readme) ? $about = nl2br(
			// mb_convert_encoding(
				file_get_contents(
					$readme
				) //,
				// 'UTF-8',
				// 'ISO-8859-1'
			// )
		) : $about = null;

		/** 
		 *	VUE : Méthode render()
		 *	Affichage avec la classe MainTemplate (vue/base/MainTemplate.php)
		 *	1/ Nom du fichier sans .php contenu dans le répertoire : vue
		 *	2/ Option : paramètres passés dans un tableau []
		 *	3/ Option (non implémentée ici) : Chemin absolu vers le fichier .php (la vue)
		 *	4/ Option (non implémentée ici) : n’inclus pas le header et le footer
		 */

		Vue::render(
			'legale/About',
			[
				'cls' => $this->getClassName(),
				'ns' => $this->getNamespace(),
				'version' => $version,
				'test' => $test,
				'readme' => $readme,
				'about' => $about,
			]
		);
	}

	private function getClassName(): string {
		return __CLASS__;
	}

	private function getNamespace(): string {
		return __NAMESPACE__;
	}
}
