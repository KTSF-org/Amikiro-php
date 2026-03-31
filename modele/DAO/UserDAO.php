<?php

namespace modele\DAO;

use modele\DAO\base\Database;
use app\util\Error;
use modele\User;
use PDO;

/** 
*	User DAO
*	Implémente l'ensemble des traitements en données pour les utilisateurs.
*	Associé à la logique métier de la classe User (modele/User.php).
*/

class UserDAO extends Database {

	/** 
	*	Deux paramètres pour le constructeur du DAO :
	*	1/ nom de la table
	*	2/ nom de la clé primaire
	*	Voir les méthodes du CRUD dans le DAO (modele/DAO/base/Database.php).
	*/

	public function __construct() {
		//-------------------------------------------
		$tableName = 'User';
		$primaryKey = 'idUser';
		//-------------------------------------------
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

	//Equivalent pour comprendre :
	// private function getAllData($metier): array {
		// return [
			// 'nom' => $metier->getNom(),
			// 'prenom' => $metier->getPrenom(),
			// 'email' => $metier->getEmail(),
			// 'ne_le' => $metier->getDateNaissance(),
			// 'ville' => $metier->getVille(),
			// 'enfants' => $metier->getEnfants(),
			// 'tel' => $metier->getTel(),
			// 'avatar' => $metier->getAvatar(),
		// ];
	// }


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
	public function read(int $idUser): mixed {
		$row = false;
		if($idUser>0)$row = $this->getOne($idUser); //on récupère la ligne/tuple concernée
		//gestion de l'index en cas d'erreur :
		if(!$row) {
			Error::setException( "l'indentifiant fourni (<b>$idUser</b>) est invalide !" );
		}
		$rowData = (array)$row; //conversion objet --> array
		unset($rowData[$this->primaryKey], $row); //retire la clé primaire du tableau et $row qui ne sert plus
		$metier = new User(...$rowData); //crée l'objet User(->User.php) avec toutes les clés du tableau $rowData
		$metier->setId($idUser); //ajoute $id dans l'objet métier (User)
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
	*	Méthode permettant l'accès aux données filtrées pour une recherche du prénom ou du nom, 
	*	avec une requête préparée.
	* 	@param string $name Nom ou prénom de l'utilisateur
	* 	@return array
	*/
	public function getUsersByName(string $Name): mixed {
		$stmt = $this->getPdo()->prepare("SELECT * FROM `" . $this->tableName . "` WHERE Name LIKE :sname OR nom LIKE :name");
		$stmt->execute([':sname' => "%$Name%", ':name' => "%$Name%"]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	*	Méthode sendSQL() implémentée dans le DAO (modele/DAO/base/Database.php)
	*	Prend en compte la commande SQL et son filtre issue du prepared statement [?]
	*	Le filtre (ici $name) est obligatoirement un tableau !
	* 	@param string $name Prénom de l'utilisateur
	* 	@return object
	*/
	public function getLineFrom(string $Name): \stdClass {
		//sendSQL() est une méthode du DAO (modele/DAO/base/Database.php)
		return $this->sendSQL("SELECT * from `" . $this->tableName . "` WHERE Name = ?", [$Name]);
	}
	
	/**
	* Utils infos
	*/
	
	public function getTableName(): string {
		return $this->tableName;
	}
	
	public function getPrimaryKey(): string {
		return $this->primaryKey;
	}
	

	//Fonction pour récuperer l'email de l'utilisateur
	public function getUserByEmail(string $Mail): mixed{
		return $this->sendSQL(
			"SELECT * FROM `" . $this->tableName . "` WHERE Mail = ?", [$Mail]
		);
	}

	
}
