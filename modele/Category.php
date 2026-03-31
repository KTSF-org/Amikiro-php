<?php

namespace modele;
use app\util\Error;
use modele\DAO\CategoryDAO;

class Category{

	private int $id=0; //La clé primaire est identifiée par $id
	
	protected $param=[]; //La liste des paramètres (ou attributs)

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