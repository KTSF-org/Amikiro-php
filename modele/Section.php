<?php

namespace modele;
use app\util\Error;
use modele\DAO\SectionDAO;

class Section{

	private int $id=0; //La clé primaire est identifiée par $id
	
	protected $param=[]; //La liste des paramètres (ou attributs)

	public function __construct( 
		private string $title='',
		private string $content='',
		private string $creationDate='',
		private string $idUser='',
		private string $idLogs=''
	
	) {

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
	public function addSection(): bool {
		$sectionDao = new SectionDAO();
		return $sectionDao->create($this);
	}

	/**
	 * GETTERS (accesseurs)
	 */
	public function getId(): int {
		return $this->id;
	}
	public function getTitle(): string {
		return $this->title;
	}
	public function getContent(): int {
		return $this->content;
	}
	public function getCreationDate(): string {
		return $this->creationDate;
	}
	public function getIdUser(): int {
		return $this->idUser;
	}
	public function getIdLogs(): string {
		return $this->idLogs;
	}

	/**
	 * SETTERS (mutateurs)
	 */

	 public function setId($id): void {
		$this->id = $id;
	}
	public function setTitle($title): void {
		$this->title = $title;
	}
	public function setContent($content): void {
		$this->content = $content;
	}
	public function setCreationDate($creationDate): void {
		$this->creationDate = $creationDate;
	}
	public function setIdUser($idUser): void {
		$this->idUser = $idUser;
	}
	public function setidLogs($idLogs): void {
		$this->idLogs = $idLogs;
	}
}	