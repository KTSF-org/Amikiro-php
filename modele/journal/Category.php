<?php

namespace modele\journal;
use app\util\Error;
use modele\DAO\journalDAO\CategoryDAO;

/**
 * Modèle métier : catégorie d'observation colonie (table Category).
 *
 * Une Category représente un type d'événement observé sur une colonie
 * (ex : "Départ chasse", "Rentrée gîte", "Pause").
 * Elle est liée aux fiches d'observation via la table de liaison ColonySection.
 *
 * La gestion des catégories (ajout/suppression) est accessible via
 * le contrôleur Category (ROLE_NATURALISTE requis).
 */
class Category {

	private int $id = 0; // Clé primaire auto-incrémentée
	protected $param = []; // Liste des noms d'attributs pour CategoryDAO::getAllData()

	public function __construct(
		private string $name='') {

		// Gestionnaire d'erreur (pour les requêtes) :
		try {
			$this->param = $this->getKey(get_object_vars($this));
			Error::checkModelArgs(get_object_vars($this), __CLASS__ , func_get_args());
		} catch (\InvalidArgumentException $e) {
			$err = "<pre>Erreur : " . $e->getMessage();
			$err .= Error::print($e->getTrace(), 1);
			exit($err);
		}
	}

	/**
	 * METHODS
	 */

	// STOCKER LA LISTE DES ATTRIBUTS
	private function getKey(array $arr): array {
		foreach($arr as $key => $value) {
			if($key==="id" or $key==="param")continue;
			$param[] = $key;
		}
		return $param;
	}

	// SORTIR LA LISTE DES ATTRIBUTS
	public function getParam(): array {
		return $this->param;
	}

	// CREATE
	public function addCategory(): bool {
		$categoryDao = new CategoryDAO();
		return $categoryDao->create($this);
	}

	// UPDATE
	public function updateCategory(): bool {
		$categoryDAO = new CategoryDAO();
		return $categoryDAO->update($this);
	}


	// DELETE
	public function delCategory(): bool{
		$categoryDAO = new CategoryDAO();
		return $categoryDAO->delete($this);
	}

	/**
	 * GETTERS (accesseurs)
	 */
	public function getId(): int {
		return $this->id;
	}

	public function getName(): string {
		return $this->name;
	}

	/**
	 * SETTERS (mutateurs)
	 */

	 public function setId($id): void {
		$this->id = $id;
	}

	public function setName($name): void {
		$this->name = $name;
	}
}
