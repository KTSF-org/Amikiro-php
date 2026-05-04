<?php

namespace controleur;

use vue\base\Ajax as Ajax;
use app\util\Request as req;
use app\util\Guard;
use modele\DAO\UserDAO as Model;
use modele\DAO\ConfigDAO;
use modele\journal\SectionColony as SectionColony;
use modele\journal\Section as Section;
use app\util\SessionLogin as SessionLogin;
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
			'findUsers'   => 'getUserBySearch',
			'liveLeave'   => 'liveLeave',
			'viewerCount' => 'getViewerCount',
			'addSectionColony' => 'addSectionCol'
		];
	}

	/**
	 * Chargement des routes AJAX
	 * Réception du tableau des requêtes de type GET pour AJAX
	 * Charge le constructeur de la classe Ajax() héritée dans : vue/ajax/base/Ajax.php
	 * @param string $message Retourne le message par défaut, ceci n'est pas indispensable.
	 */
	public function __construct(string $message='') {
		Guard::requireLogin();

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
	protected function getUserBySearch(): mixed
	{
		// attention $_POST n'est pas sécurisé !
		// $nom = $_POST['name'] ?? ''; //??=opérateur nullable, équivalent à isset
		$nom = req::post('name'); //$_POST sécurisé avec la méthode Request (app/util/Request.php)
		$user = $this->db->getUsersByName($nom);
		if (empty($nom))
			$user = false;
		if ($user !== false && \app\util\SessionLogin::getRole() === ROLE_ADMIN) {
			$_SESSION['searched_user'] = $user;
		}
		return $user;
	}

	/**
	 * Décrémente le compteur de viewers actifs quand l'utilisateur quitte la page Live.
	 * Appelé via navigator.sendBeacon() au beforeunload.
	 */
	protected function liveLeave(): bool
	{
		if (isset($_SESSION['in_live'])) {
			(new ConfigDAO())->decrementViewers();
			unset($_SESSION['in_live']);
		}
		return true;
	}

	/**
	 * Retourne le nombre de viewers actifs en temps réel.
	 * Interrogé périodiquement par la vue Live via setInterval.
	 */
	protected function getViewerCount(): int
	{
		return (int) ((new ConfigDAO())->getURLbyId(1)['viewerCount'] ?? 0);
	}

	protected function addSectionCol():string
	{
		$message = "Les champs ne sont pas rempli";
		if (req::has('title')) {
		$title = req::post('title');
		$date = req::post('date');
		$category = req::post('category');
		$notes = req::post('notes');
		
		
			$section = new Section($title, $notes, $date, SessionLogin::getUserId()); //création de la rubrique

			if ($section->addSection()) {  //création de la rubrique en bdd
				$sectionColony = new SectionColony($section->getId(), (int) $category); //création de la section Colony
				$sectionColony->addSectionColony(); //création de la section colony en bdd
				return "Success";
		}	
			return "Successsss";

		}else{
			return "No success";
		}


	

	
				
		

	}

}
