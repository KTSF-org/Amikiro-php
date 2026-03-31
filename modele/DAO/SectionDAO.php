<?php

namespace modele\DAO;

use modele\DAO\base\Database;
use PDO;

/**
 * SectionDAO — Accès aux données de suivi des modifications
 *
 * Gère les événements utilisateurs (Modifications, Création, etc.).
 */
class SectionDAO extends Database {

    public function __construct() {
        $tableName = 'Section';
		$primaryKey = 'IdSection';
        parent::__construct($tableName, $primaryKey);
    }

	/** 
	*	Besoins en données issues du métier User (modele/User.php)
	*	@param object:metier Instance de l'objet métier
	*	@return array
	*/
	private function getAllData($metier): array {
		$data = [];
		$keys = $metier->getParam();

		foreach ($keys as $key) {
			$methodName = 'get' . ucfirst($key);
			if (method_exists($metier, $methodName)) {
				$data[$key] = $metier->$methodName();
			} else {
				$data[$key] = null; 
			}
		}

		return $data;
	}

    /** 
	*	CRUD : create
	*	@param object:metier Instance de l'objet métier
	*	@return bool
	*/
	public function create($metier): bool {
		$data = $this->getAllData($metier);
		//createOne() et getLastKey() sont des méthodes du DAO (modele/DAO/base/Database.php)
		$bool = $this->createOne($data);
		$metier->setId( $this->getLastKey() );
		return $bool;
	}

	/** 
	*	CRUD : read
	*	@param integer Numéro de la clé primaire
	*	@return mixed object|string|bool
	*/
	public function read(int $id=1): mixed {
		$row = false;
		if($id>0)$row = $this->getOne($id); //on récupère la ligne/tuple concernée
		//gestion de l'index en cas d'erreur :
		if(!$row) {
			Error::setException( "l'identifiant fourni (<b>$id</b>) est invalide !" );
		}
		$rowData = (array)$row; //conversion objet --> array
		unset($rowData[$this->primaryKey], $row); //retire la clé primaire du tableau et $row qui ne sert plus
		$metier = new Section(...$rowData); //crée l'objet Section(->Section.php) avec toutes les clés du tableau $rowData
		$metier->setId($id); //ajoute $id dans l'objet métier (User)
		return $metier; //retourne l'objet crée
	}
	
	/** 
	*	CRUD : update
	*	@param object:metier Instance de l'objet métier
	*	@return bool
	*/
	public function update($metier): bool {
		$data = $this->getAllData($metier);
		//updateOne() est une méthode du DAO (modele/DAO/base/Database.php)
		return $this->updateOne($metier->getId(), $data);
	}
	
	/** 
	*	CRUD : delete
	*	@param object:metier Instance de l'objet métier
	*	@return bool
	*/
	public function delete($metier): bool {
		//deleteOne() est une méthode du DAO (modele/DAO/base/Database.php)
		return $this->deleteOne( $metier->getId() );
	}
    /**
     * Retourne les sections triées par CreationDate.
     * @return \stdClass[]
     */
    public function findAll(): array {
        try {
            $stmt = $this->getPdo()->prepare(
                "SELECT IdSection, Title FROM `Section` ORDER BY CreationDate DESC"
            );
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ) ?: [];
        } catch (\PDOException $e) {
            error_log('[SectionDAO::findAll] ' . $e->getMessage());
            return [];
        }
    }

}