<?php

namespace modele;
use app\util\Error;
use modele\DAO\LogsDAO;

class Logs{

	private int $id=0; //La clé primaire est identifiée par $id
	
	protected $param=[]; //La liste des paramètres (ou attributs)

	public function __construct( 
		private string $action='',
		private string $targetTable='',
		private int $idTarget='',
		private int $idUser='',
		private string $logDate=''
	
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
	public function addLogs(): bool {
		$logsDao = new LogsDAO();
		return $logsDao->create($this);
	}

	/**
	 * GETTERS (accesseurs)
	 */
	public function getId(): int {
		return $this->id;
	}
	public function getAction(): string {
		return $this->action;
	}
	public function getTargetTable(): string {
		return $this->targetTable;
	}
	public function getIdTarget(): int {
		return $this->idTarget;
	}
	public function getIdUser(): int {
		return $this->idUser;
	}
	public function getLogDate(): string {
		return $this->logDate;
	}

	/**
	 * SETTERS (mutateurs)
	 */

	public function setId($id): void {
		$this->id = $id;
	}
	public function setAction($action): void {
		$this->action = $action;
	}
	public function setTargetTable($targetTable): void {
		$this->targetTable = $targetTable;
	}
	public function setIdTarget($idTarget): void {
		$this->idTarget = $idTarget;
	}
	public function setIdUser($idUser): void {
		$this->idUser = $idUser;
	}
	public function setLogDate($logDate): void {
		$this->logDate = $logDate;
	}
}

	