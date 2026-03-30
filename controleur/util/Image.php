<?php

namespace controleur\util;

use Exception;

/**
 *	Classe pour la gestion des images png en back-end depuis la route : 
 *	http://localhost/apptest/img?f=nom_de_l_image_sans_extension_png
 *	L'image en question est chargée depuis : app/file/image/
 */

class Image {

	public function __construct() {

		$im = $_GET['f'] ?? null;

		$this->renderPNG(FILE_DIR . 'image/' . $im . '.png');
	}

	private function renderPNG($img) {

		try {

			// Vérification de l'existence du fichier
			if (!file_exists($img)) {
				throw new Exception("Le fichier image n'existe pas : " . $img);
			}

			$image = new \claviska\SimpleImage();
			$im = $image
				->fromFile($img)
				->bestFit(680, 680)
				->toScreen('image/png');

			return $im;
		} catch (Exception $err) {
			// echo $err->getMessage(); // Décommenter pour afficher le message d'erreur
			$im = ROOT . 'asset/img/img-err.png';

			if (file_exists($im)) {
				header("Content-type: image/png");
				echo file_get_contents($im);
				exit;
			} else {
				die("Fichier introuvable !");
			}
		}
	}
}
