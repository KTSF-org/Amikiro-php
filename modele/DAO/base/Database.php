<?php

namespace modele\DAO\base;

use PDO;

/**
 * Classe de base de tous les DAOs — fournit le CRUD générique.
 *
 * Chaque DAO concret (UserDAO, BatDAO…) hérite de cette classe et
 * passe le nom de sa table + sa clé primaire au constructeur parent.
 *
 * Connexion PDO :
 *   - $pdo est un singleton statique partagé entre tous les DAOs.
 *   - Initialisé à la première utilisation via Connect::run() (??=).
 *   - Accès via getPdo() — ne jamais utiliser $pdo directement depuis l'extérieur.
 *
 * Méthodes personnalisées (requêtes spécifiques) :
 *   Utiliser getPdo()->prepare() dans le DAO fils plutôt que d'étendre les méthodes génériques.
 *
 * ⚠ deleteOne($id, true) et deleteMany($ids, true) sont NON FONCTIONNELS :
 *   PDO ne supporte pas les multi-instructions dans une seule exec() — les FK checks
 *   ne sont pas désactivés. Ne jamais passer $disableConstraintKey = true.
 */
class Database implements IDatabase {

    protected $tableName = '';
    protected $primaryKey = '';
	
	private int $lastId;

    /**
     * @var $pdo PDO|null
     */
    private static ?PDO $pdo = null;

    /**
     * @return PDO
     */
    public static function getPdo(): PDO {
		
		//Avec l'opérateur null coalescing (??=)
		return self::$pdo ??= Connect::run();
		
		//Sans
        // if (self::$pdo == null) {
            // self::$pdo = Connect::run();
        // }

        // return self::$pdo;
    }

    /**
     * @param String $tableName Nom de la table
     * @param String $primaryKey Clé primaire de la table
     */
    public function __construct(string $tableName, string $primaryKey = 'id') {
        $this->tableName = $tableName;
        $this->primaryKey = $primaryKey;
    }
	
	/** 
	 * Récupère la dernière clé primaire créée (voir : createOne )
	 * @return integer
	 */
	public function getLastKey(): int {
		//return $this->getPdo()->lastInsertId(); //instable, mais possible
		return $this->lastId;
	}

    /**
     * Lance une requête SQL préparée avec un filtre "prepared statement" en tableau
	 * @param string $cmd
	 * @param array $filter
     * @return \stdClass|null
     */
    public function sendSQL(string $cmd, array $filter): \stdClass|null|bool {
        $stmt = $this->getPdo()->prepare($cmd);
        $stmt->execute($filter);
        return $stmt->fetch(PDO::FETCH_OBJ); //FETCH_ASSOC : array
    }

        public function sendSQLAssoc(string $cmd, array $filter): array |null|bool {
        $stmt = $this->getPdo()->prepare($cmd);
        $stmt->execute($filter);
        return $stmt->fetch(PDO::FETCH_ASSOC);
        }


    /**
     * Retourne l'ensemble des enregistrements présent en base de données (pour la table $tableName)
     * @return \stdClass|null
     */
    public function getAll(): array|null {
        $stmt = self::getPdo()->prepare("SELECT * FROM {$this->tableName};");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Permet la récupération d'un enregistrement en base de données
     * @param String $id
     * @return \stdClass|null
     */
    public function getOne(string $id): \stdClass|bool {
        $stmt = self::getPdo()->prepare("SELECT * FROM {$this->tableName} WHERE {$this->primaryKey} = ? LIMIT 1");
        $stmt->execute([$id]);
		try {
			return $stmt->fetch(PDO::FETCH_OBJ);
		} catch (PDOException $e) {
			error_log("Method : getOne : Error getting record: " . $e->getMessage());
			return false; 
		}
    }

    /**
     * Permet la création de l'ensemble des champs passés en paramètre dans $data.
     * @param array $data
     * @return bool
     */
	public function createOne(array $data = []): bool {
		$bool=false;
		$columns = array_keys($data);
		$placeholders = array_fill(0, count($columns), '?');

		$query = "INSERT INTO {$this->tableName} (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $placeholders) . ")";
		$stmt = self::getPdo()->prepare($query);
		$values = array_values($data);

		try {
			//Stock le boolean et enregistre le n° de la dernière clé créée :
			$bool = $stmt->execute($values); 
			$stmt = self::getPdo()->query("SELECT LAST_INSERT_ID()");
			$this->lastId = $stmt->fetchColumn();
			return $bool;
		} catch (PDOException $e) {
			error_log("Method : createOne : Error creating record: " . $e->getMessage());
			return $bool; 
		}
	}

    /**
     * Permet la mise à jour de l'ensemble des champs passée en paramètre dans $data pour l'enregistrement à $id.
     * @param $id
     * @param array $data
     * @return bool
     */
    public function updateOne(string $id, array $data = []): bool {
        $query = "UPDATE {$this->tableName} SET ";

        foreach ($data as $columnName => $columnValue) {
            $query .= $columnName . " = :$columnName, ";
        }
        $query = rtrim($query, ", ");

        $query .= " WHERE {$this->primaryKey} = :id";
        $stmt = self::getPdo()->prepare($query);
        return $stmt->execute(array_merge(["id" => $id], $data));
    }
	
    /**
     * Supprime l'enregistrement $id dans la table $tableName
     * @param $id
     * @param $disableConstraintKey
     * @return bool
     */
    public function deleteOne(string $id, bool $disableConstraintKey=false): bool {
		$sql = "DELETE FROM {$this->tableName} WHERE {$this->primaryKey} = ? LIMIT 1;";
		
		//On désactive temporairement les "key constraint" et on réactive au besoin :
		$append = "SET FOREIGN_KEY_CHECKS=0; ";
		$prepend = " SET FOREIGN_KEY_CHECKS=1";
		if($disableConstraintKey) $sql = $append . $sql . $prepend;
		
        $stmt = self::getPdo()->prepare($sql);
        return $stmt->execute([$id]);
    }
	
    /**
     * Supprime plusieurs enregistrements $id[] dans la table $tableName
	 * @prototype
     * @param $id type array
     * @param $disableConstraintKey
     * @return integer
     */
	public function deleteMany(array $id, bool $disableConstraintKey=false): int {
		// Aucun ID à supprimer
		if (empty($id)) {
			return 0; 
		}

		$placeholders = implode(',', array_fill(0, count($id), '?')); 
		$sql = "DELETE FROM {$this->tableName} WHERE {$this->primaryKey} IN ($placeholders);";

		//On désactive temporairement les "key constraint" et on réactive au besoin :
		$append = "SET FOREIGN_KEY_CHECKS=0; ";
		$prepend = " SET FOREIGN_KEY_CHECKS=1";
		if($disableConstraintKey) $sql = $append . $sql . $prepend;

		$stmt = self::getPdo()->prepare($sql);
		$stmt->execute($id);

		return $stmt->rowCount(); 
	}
	
}
