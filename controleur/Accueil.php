<?php

namespace controleur;

use modele\DAO\UserDAO as Model;
use app\util\Request as req;
use vue\base\MainTemplate as Vue;

class Accueil {

	public function __construct() {
		/**
		 *	--------------
		 *	    MODELE
		 *	--------------
		 */

		/**
		 *	MODELE : Instance de UserDAO
		 */
		$db = new Model();
		
		/*** A TESTER : CREATE ***/
		// $user = new \modele\User("Nid", "Joe", "joni@mail.live", "1950-10-10", "Carcassonne", "4", "012345678", "3aebe2e808654948268dd4a18ffaadc4");
		// if($user->addUser()) { //Appel de la méthode addUser() sur la classe métier User.php
			// echo "Utilisateur (" . $user->getEmail() . ") crée avec succès !"; 
		// }
		// die;
		
		/*** A TESTER : READ ***/
		// $data = $db->read(2); //numéro de la clé primaire, tuple en instance (voir modele/User.php)
		// die( 'ID n°' . $data->getId() . ' : ' . $data->getPrenom() . ' ' . $data->getNom() );
		
		/*** A TESTER : UPDATE ***/
		// $data = $db->read(2); //l'id de la clé primaire est normalement reçu par une SESSION, attention pas $_GET !
		// $data->setPrenom('Maria'); //les setters reçus par $_POST ...
		// $data->setNom('Carré');
		// $data->setAvatar('154ea989b5eeb917c6162117bdb7940c');
		// $db->update($data); //retourne un booléen ,ici non implémenté
		
		/*** A TESTER : DELETE ***/
		// $data = $db->read(5);
		// $info = 'ID n°' . $data->getId() . ' : ' . $data->getPrenom() . ' ' . $data->getNom();
		// if( $db->delete($data) ) echo "Suppression : " . $info;
		// die;

		/**
		 *	Méthodes issues de la classe UserDAO, elle-même héritée du DAO :
		 */
		// $data = $db->getUsersByName('Bob'); // retourne un array
		$data = $db->getLineFrom('Marco'); // retourne un objet
		$allData = $db->getAll();
		$table = $db->getTableName();

		/**
		 *	Fonction debug() accessible partout (app/functions.php)
		 *	Affiche les données (print_r) et stop l'application :
		 */
		// debug($data, $allData, $table);

		/**
		 *	---------------
		 *	    SESSION
		 *	---------------
		 */

		/**
		 *	Session utilisateur :
		 */
		if(!isset($_SESSION['user'])) { 
			$_SESSION['user'] = (array)$data; //conversion objet --> array
		}
		
		/**
		 *	-------------
		 *	    POST
		 *	-------------
		 */
		
		$test = 'Avatar par défaut';
		//Utilisation de la classe Request (app/util/Request.php)
		if(req::has('helloPost')) {
			$test = req::post('helloPost');
		}

		/**
		 *	-------------
		 *	    VUES
		 *	-------------
		 */

		/**
		 *	VUE : Méthode setTitle()
		 *	Ajoute une string sur la balise <title> de la page courante
		 */
		Vue::setTitle('Bienvenue');

		/**
		 *	Style ou JavaScript supplémentaires (dépendances) que l'on
		 *	souhaite ajouter dans la vue, partie <head></head>
		 *	Voir le fichier : vue/common/header.php
		 */
		Vue::addCSS([
			ASSET . '/css/accueil.css',
		]);

		// ICI pour l'exemple.
		// Il est préférable d'ajouter JQuery dans : vue/common/header.php
		Vue::addJS([
			'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js',
		]);

		/**
		 *	VUE : Méthode test() :
		 *	Affiche les chemins utilisés pour la vue et arrête l'application
		 *	test() doit être placé avant render()
		 */
		// Vue::test();

		/** 
		 *	VUE : Méthode render()
		 *	Affichage avec la classe MainTemplate (vue/base/MainTemplate.php)
		 *	1/ Nom du fichier sans .php contenu dans le répertoire : vue
		 *	2/ Option : paramètres passés dans un tableau []
		 *	3/ Option (non implémentée ici) : Chemin absolu vers le fichier .php (la vue)
		 *	4/ Option (non implémentée ici) : n’inclus pas le header et le footer
		 */
		Vue::render('Accueil', [
			'test' => $test,
			'param' => [
				'a' => 1,
				'b' => 2,
				'c' => 3
			],
			'data' => $_SESSION['user'],
			'allData' => $allData,
			'table' => $table,
		]);

	}
}
