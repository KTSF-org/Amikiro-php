<?php

namespace controleur;

use vue\base\Ajax as Ajax;
use app\util\Request as req;
use modele\DAO\UserDAO as Model;

/**
 *	Classe chargée depuis le routing : route/routing.php
 *	==> $route->add('/ajax', 'controleur\MainAjax'); 
 *	Pour TESTER : http://localhost/apptest/ajax
 *	Avec le paramètre : 
 *	http://localhost/apptest/ajax?findUsers
 *	==> retourne false car aucun $_POST['name'], voir :
 *	vue/ajax/ajaxRechercher.php , ligne 36
 */

class MainAjax extends Ajax {

	private $db;

	/**
	 *	--------------------
	 *	   AJAX : ROUTES
	 *	--------------------
	 */

	/**
	 * Collection par un tableau des requêtes de type GET pour AJAX
	 * Est ajouté [ 'AjaxNom' => 'methodNom' ] : 
	 * 1/ AjaxNom = le nom reçu par l'url : /ajax?nom
	 * 2/ methodNom = le nom de la méthode utilisée dans cette classe.
	 */
	private function ajaxRoute(): array {
		return [
			// - "findUsers" est utilisé dans : vue/ajax/ajaxRechercher.php
			// - la méthode protégée : "getUserBySearch" est implémentée ci-dessous.
			'findUsers' => 'getUserBySearch',
			// - D'autres lignes ?
		];
	}

	/**
	 * Chargement des routes AJAX
	 * Réception du tableau des requêtes de type GET pour AJAX
	 * Charge le constructeur de la classe Ajax() héritée dans : vue/ajax/base/Ajax.php
	 * @param string $message Retourne le message par défaut, ceci n'est pas indispensable.
	 */
	public function __construct(string $message='') {

		if(!empty($message)) {
			$this->message = $message;
		}

		$this->db = new Model();
		
		$route = $this->ajaxRoute();
		
		foreach($route as $k => $v) {
			if( isset($_GET[$k]) ) {
				$this->method = $v; //méthode héritée de : vue/base/Ajax.php
			}
		}

		//constructeur de la classe Ajax() (vue/base/Ajax.php) :
		parent::__construct();
	}
	
	/**
	 *	--------------------
	 *	   AJAX : METHODS
	 *	--------------------
	 */

	/**
	 * Nom de la méthode qui sera chargée dans ce constructeur avec $this->method
	 * ==> voir au-dessus le tableau dans la fonction ajaxRoute().
	 * @return mixed Retourne le nom ou le prénom recherché, ou false
	 */
	protected function getUserBySearch(): mixed {
		// attention $_POST n'est pas sécurisé !
		// $nom = $_POST['name'] ?? ''; //??=opérateur nullable, équivalent à isset
		$nom = req::post('name'); //$_POST sécurisé avec la méthode Request (app/util/Request.php)
		$user = $this->db->getUsersByName($nom);
		if (empty($nom)) $user = false;
		if($user!==false)$_SESSION['user'] = $user;
		return $user;
	}
	
	
}
