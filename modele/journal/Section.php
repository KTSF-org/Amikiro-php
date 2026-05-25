<?php

namespace modele\journal;
use app\util\Error;
use modele\DAO\journalDAO\SectionDAO;

/**
 * Modèle métier : fiche d'observation (table Section).
 *
 * Une Section est le tronc commun de toutes les fiches du journal.
 * Elle contient le titre, le contenu textuel, la date de l'événement
 * et l'identifiant de l'auteur.
 *
 * Chaque Section est ensuite spécialisée par une table de liaison :
 *   - SectionSpecimen (SpecimenSection) → fiche individu (Bat)
 *   - ColonySection                     → fiche colonie (Category)
 *
 * Les noms des propriétés du constructeur doivent correspondre exactement
 * aux noms de colonnes de la table SQL Section.
 */
class Section {

	private int $id = 0; // Clé primaire — jamais dans le constructeur (auto-incrémentée)
	protected $param = []; // Liste des noms d'attributs utilisée par SectionDAO::getAllData()

	public function __construct(
		private string $title='',
		private string $content='',
		private string $eventDate='',
		private string $modifDate='',
		private int $idUser=0,
		private int $idLogs=1,
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

	// UPDATE
	public function updateSection() : bool {
		$sectionDAO = new SectionDAO();
        return $sectionDAO->update($this);
	}

	// DELETE
	public function deleteSection(): bool {
		$sectionDao = new SectionDAO();
		return $sectionDao->delete($this);
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
	public function getContent(): string {
		return $this->content;
	}
	public function getEventDate(): string {
		return $this->eventDate;
	}
	public function getModifDate(): string {
		return $this->modifDate;
	}
	public function getIdUser(): int {
		return $this->idUser;
	}

	public function getIdLogs(): int {
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
	public function setEventDate($eventDate): void {
		$this->eventDate = $eventDate;
	}
	public function setModifDate($modifDate): void {
		$this->modifDate = $modifDate;
	}
	public function setIdUser($idUser): void {
		$this->idUser = $idUser;
	}
	public function setIdLogs($idLogs): void {
		$this->idLogs = $idLogs;
	}
}
