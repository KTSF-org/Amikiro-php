<?php

namespace controleur;

use modele\DAO\journalDAO\SectionDAO;
use modele\journal\Category;
use vue\base\Ajax as Ajax;
use app\util\Request as req;
use app\util\Guard;
use modele\DAO\UserDAO as Model;
use modele\DAO\ConfigDAO;
use modele\journal\SectionColony as SectionColony;
use modele\journal\Section as Section;
use app\util\SessionLogin as SessionLogin;
use DateTime;
use modele\DAO\journalDAO\CategoryDAO;
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
			'addSectionColony' => 'addSectionCol',
			'updateSectionColony' => 'updateSectionCol',
			'addCategory' => 'addCategory',
			'getCategories' => 'getCategories',
			'delCategory' => 'delCategory',
			'updateCategory' => 'updateCategory',
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
	protected function getUserBySearch(): array {
		$nom   = req::post('name'); //$_POST sécurisé avec la méthode Request (app/util/Request.php)
		$users = empty($nom) ? [] : $this->db->getUsersByName($nom);
		return $users;
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
		if (SessionLogin::getRole() < ROLE_NATURALISTE) return "No success";
		if (req::has('title')) {
		$title = req::post('title');
		$date = req::post('date');
		$category = req::post('category');
		$notes = req::post('notes');

			if(!is_numeric($category)){  ////création d'une category si on ne reçoit pas un id
				$newCategory = new Category($category);
				$newCategory->addCategory();
				$category= $newCategory->getId();
			}

			$now = new DateTime();
			$dateModif= $now->format("Y-m-d H:i:s");

			$section = new Section($title, $notes, $date, $dateModif,SessionLogin::getUserId()); //création de la rubrique


			if ($section->addSection()) {  //création de la rubrique en bdd
				$sectionColony = new SectionColony($section->getId(), (int) $category); //création de la section Colony
				$sectionColony->addSectionColony(); //création de la section colony en bdd
				return "Success";
			}
			return "No success";

		}else{
			return "No success";
		}

	}

	protected function updateSectionCol():string
	{
		if (SessionLogin::getRole() < ROLE_NATURALISTE) return "No success";
		if(req::has('title')){
			$title = req::post('title');
			$date = req::post('date');
			$category = req::post('category');
			$notes = req::post('notes');
			$id = req::post('sectionId');

			if(!is_numeric($category)){   //création d'une category si on ne reçoit pas un id
				$newCategory = new Category($category);
				$newCategory->addCategory();
				$category= $newCategory->getId();
			}

			$now = new DateTime();
			$dateModif= $now->format("Y-m-d H:i:s");
			$section = new Section($title, $notes, $date,$dateModif, SessionLogin::getUserId());
			$section->setId($id);
			$sectionDAO = new SectionDAO();

			if($sectionDAO->update($section)){
				$sectionColony = new SectionColony($section->getId(), (int) $category);
				$sectionColony->addSectionColony();
				return "Success";
			}
			return "No success";

		}else{
			return "No success";
		}
	}

	protected function addCategory():string
	{
		if (SessionLogin::getRole() < ROLE_NATURALISTE) return "No success";
		if(req::has('name')){
			$name = req::post('name');

			$newCategory = new Category($name);
			$newCategory->addCategory();

			return "Success";
		}else{
			return "No success";
		}
	}

	protected function getCategories(): array //affichage categories dans le datatable
	{
    	$cat = new CategoryDAO();
    	$allCategories = $cat->getAllCategories();
    	$data = [];

    	foreach($allCategories as $category){
        	$data[] = [
            	'id'   => $category->getId(),
            	'name' => $category->getName(),
        	];
    	}

    	return $data;
	}

	protected function delCategory() :string //suppression d'une categorie
	{
		if (SessionLogin::getRole() < ROLE_NATURALISTE) return "No success";
		if(req::has('id')){
			$id = req::post('id');

			$cat = new CategoryDAO();
			$category = $cat->findById($id);
			$category->delCategory();

			return "Success";
		}else{
			return "No success";
		}
	}


	protected function updateCategory() :string
	{
		if (SessionLogin::getRole() < ROLE_NATURALISTE) return "No success";
		if(req::has('id') || req::has('name')){
			$id = req::post('id');
			$name = req::post('name');

			$category = new Category($name);
			$category->setId($id);

			if($category->updateCategory()){
				return "Success";
			}

			return "Successsss";

		}else{
			return "No success";
		}
	}

}
