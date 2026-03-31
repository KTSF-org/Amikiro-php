<?php

namespace modele\DAO;

use modele\DAO\base\Database;
use app\util\Error;
use modele\Bat;
use PDO;
use UnexpectedValueException;

class BatDAO extends Database
{
    /** 
     *	Deux paramètres pour le constructeur du DAO :
     *	1/ nom de la table
     *	2/ nom de la clé primaire
     *	Voir les méthodes du CRUD dans le DAO (modele/DAO/base/Database.php).
     */
    public function __construct()
    {
        $tableName = "Bat";
        $primaryKey = "id";
        parent::__construct($tableName, $primaryKey);
    }


    private function getAllData($bat): array
    {
        $data = [];
        $keys = $bat->getParam();

        foreach ($keys as $key) {
            $methodName = "get" . ucfirst($key);
            if (method_exists($bat, $methodName)) {
                $data[$key] = $bat->$methodName();
            } else {
                $data[$key] = null;
            }
        }
        return $data;
    }

    // CRUD CREATE: créer la chauve-souris dans la BDD
    public function create($bat): bool
    {
        $data = $this->getAllData($bat);
        $bool = $this->createOne($data);
        $bat->setId($this->getLastKey());
        return $bool;
    }

    // CRUD READ: Retourne une chauve-souris depuis la BDD grâce à un id
    public function getBatById(int $id): mixed
    {
        $row = false;
        if($id>0)
            $row = $this->getOne($id);
        if (!$row)
            Error::setException( "l'indentifiant fourni (<b>$id</b>) est invalide !" );
        $rowData = (array) $row;
        unset($rowData[$this->primaryKey], $row);
        $bat = new Bat(...$rowData);
        $bat->setId($id);
        return $bat;
    }

    // CRUD READ: Retourne toutes les chauve-souris
    //public function getAllBat(): mixed {}


    // CRUD UPDATE: met à jour la chauve-souris dans la BDD
    public function update($bat): bool
    {
        $data = $this->getAllData($bat);
        return $this->updateOne($bat->getId(), $data);
    }

    // CRUD DELETE: supprime la chauve-souris dans la BDD
    public function delete($bat) : bool {
        return $this->deleteOne($bat->getId());
    }

}
