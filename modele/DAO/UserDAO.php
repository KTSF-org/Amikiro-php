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

class UserDAO extends Database
{

	/**
	 *	Deux paramètres pour le constructeur du DAO :
	 *	1/ nom de la table
	 *	2/ nom de la clé primaire
	 *	Voir les méthodes du CRUD dans le DAO (modele/DAO/base/Database.php).
	 */

	public function __construct()
	{
		//-------------------------------------------
		$tableName = 'User';
		$primaryKey = 'id';
		//-------------------------------------------
		parent::__construct($tableName, $primaryKey);
	}

	/**
	 *	Besoins en données issues du métier User (modele/User.php)
	 *	@param object:metier Instance de l'objet métier
	 *	@return array
	 */
	private function getAllData($metier): array
	{
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
	public function create($metier): bool
	{
		$data = $this->getAllData($metier);
		//createOne() et getLastKey() sont des méthodes du DAO (modele/DAO/base/Database.php)
		$bool = $this->createOne($data);
		$metier->setId($this->getLastKey());
		return $bool;
	}

	/**
	 *	CRUD : read
	 *	@param integer Numéro de la clé primaire
	 *	@return mixed object|string|bool
	 */
	public function getUsersById(int $id): mixed
	{
		$row = false;
		if ($id > 0)
			$row = $this->getOne($id); //on récupère la ligne/tuple concernée
		//gestion de l'index en cas d'erreur :
		if (!$row) {
			Error::setException("l'indentifiant fourni (<b>$id</b>) est invalide !");
		}
		$rowData = (array) $row; //conversion objet --> array
		unset($rowData[$this->primaryKey], $row); //retire la clé primaire du tableau et $row qui ne sert plus
		$rowData['memberNum'] = (string) ($rowData['memberNum'] ?? '');
		$metier = new User(...$rowData); //crée l'objet User(->User.php) avec toutes les clés du tableau $rowData
		$metier->setId($id); //ajoute $id dans l'objet métier (User)
		return $metier; //retourne l'objet crée
	}

	/**
	 *	CRUD : update
	 *	@param object:metier Instance de l'objet métier
	 *	@return bool
	 */
	public function update($metier): bool
	{
		$data = $this->getAllData($metier);
		//updateOne() est une méthode du DAO (modele/DAO/base/Database.php)
		return $this->updateOne($metier->getId(), $data);
	}

	/**
	 *	CRUD : delete
	 *	@param object:metier Instance de l'objet métier
	 *	@return bool
	 */
	public function delete($metier): bool
	{
		//deleteOne() est une méthode du DAO (modele/DAO/base/Database.php)
		return $this->deleteOne($metier->getId());
	}

	/**
	 *	Méthode permettant l'accès aux données filtrées pour une recherche du prénom ou du nom,
	 *	avec une requête préparée.
	 * 	@param string $name Nom ou prénom de l'utilisateur
	 * 	@return array
	 */
	public function getUsersByName(string $name): array
	{
		$stmt = $this->getPdo()->prepare("SELECT * FROM `" . $this->tableName . "` WHERE name LIKE :name OR surname LIKE :surname ORDER BY surname, name LIMIT 10");
		$stmt->execute([':surname' => "%$name%", ':name' => "%$name%"]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	/**
	 *	Méthode sendSQL() implémentée dans le DAO (modele/DAO/base/Database.php)
	 *	Prend en compte la commande SQL et son filtre issue du prepared statement [?]
	 *	Le filtre (ici $name) est obligatoirement un tableau !
	 * 	@param string $name Prénom de l'utilisateur
	 * 	@return object
	 */
	public function getLineFrom(string $name): \stdClass
	{
		//sendSQL() est une méthode du DAO (modele/DAO/base/Database.php)
		return $this->sendSQL("SELECT * from `" . $this->tableName . "` WHERE name = ?", [$name]);
	}

	/**
	 * Utils infos
	 */

	public function getTableName(): string
	{
		return $this->tableName;
	}

	public function getPrimaryKey(): string
	{
		return $this->primaryKey;
	}


	/**
	 * Retourne les utilisateurs filtrés par rôle avec pagination.
	 * @param int $role  -1 = tous les rôles
	 */
	public function getAllFiltered(int $role = -1, int $offset = 0, int $limit = 20): array
	{
		if ($role >= 0) {
			$stmt = $this->getPdo()->prepare(
				"SELECT * FROM `{$this->tableName}` WHERE codeRole = ? ORDER BY surname, name LIMIT ? OFFSET ?"
			);
			$stmt->bindValue(1, $role, PDO::PARAM_INT);
			$stmt->bindValue(2, $limit, PDO::PARAM_INT);
			$stmt->bindValue(3, $offset, PDO::PARAM_INT);
		} else {
			$stmt = $this->getPdo()->prepare(
				"SELECT * FROM `{$this->tableName}` ORDER BY surname, name LIMIT ? OFFSET ?"
			);
			$stmt->bindValue(1, $limit, PDO::PARAM_INT);
			$stmt->bindValue(2, $offset, PDO::PARAM_INT);
		}
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * Compte le nombre d'utilisateurs (optionnellement filtrés par rôle).
	 * @param int $role  -1 = tous les rôles
	 */
	public function countFiltered(int $role = -1): int
	{
		if ($role >= 0) {
			$stmt = $this->getPdo()->prepare(
				"SELECT COUNT(*) FROM `{$this->tableName}` WHERE codeRole = ?"
			);
			$stmt->execute([$role]);
		} else {
			$stmt = $this->getPdo()->query("SELECT COUNT(*) FROM `{$this->tableName}`");
		}
		return (int) $stmt->fetchColumn();
	}

	/**
	 * Incrémente le compteur de connexions de l'utilisateur.
	 */
	public function incrementConnectCount(int $id): void
	{
		$this->getPdo()->prepare(
			"UPDATE `" . $this->tableName . "` SET countConnect = countConnect + 1 WHERE id = ?"
		)->execute([$id]);
	}

	//Fonction pour récuperer l'email de l'utilisateur
	public function getUserByEmail(string $mail): mixed
	{
		return $this->sendSQL(
			"SELECT * FROM `" . $this->tableName . "` WHERE mail = ?",
			[$mail]
		);
	}

    public function generateNextMemberNum(): string {
        $year = (int)date('Y');
        $row  = $this->sendSQL(
            "SELECT memberNum FROM User
             WHERE memberNum LIKE ?
             ORDER BY CAST(SUBSTRING_INDEX(memberNum, '-', -1) AS UNSIGNED) DESC
             LIMIT 1",
            ["AMI-{$year}-%"]
        );

        if (!$row) {
            return "AMI-{$year}-0001";
        }

        $parts = explode('-', $row->memberNum); // ['AMI', '2026', '0042']
        $next  = (int)end($parts) + 1;

        return "AMI-{$year}-" . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

	public function findAll(): array
	{
		$allUsers = array();
		$data = (array) $this->getAll();
		foreach ($data as $elem) {
			$rowData = (array) $elem;
			$id = $rowData[$this->primaryKey];
			unset($rowData[$this->primaryKey], $elem);
			$user = new User(...$rowData);
			$user->setId($id);
			array_push($allUsers, $user);
		}
		return $allUsers;
	}
}
