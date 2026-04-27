<?php

namespace modele\DAO\journalDAO;

use ArrayObject;
use modele\DAO\base\Database;
use app\util\Error;
use modele\journal\Species;
use PDO;
use UnexpectedValueException;

class SpeciesDAO extends Database
{

    public function __construct()
    {
        $tableName = "Species";
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

        // CRUD CREATE: créer l'espece dans la BDD
    public function create($species): bool
    {
        $data = $this->getAllData($species);
        $bool = $this->createOne($data);
        $species->setId($this->getLastKey());
        return $bool;
    }

    // CRUD READ: Retourne une espèce depuis la BDD grâce à un id
    public function getSpeciesById(int $id): mixed
    {
        $row = false;
        if($id>0)
            $row = $this->getOne($id);
        if (!$row)
            Error::setException( "l'indentifiant fourni (<b>$id</b>) est invalide !" );
        $rowData = (array) $row;
        unset($rowData[$this->primaryKey], $row);
        $species = new Species(...$rowData);
        $species->setId($id);
        return $species;
    }

    // CRUD READ: Retourne toutes les espèces depuis la BDD
    public function getAllSpecies() : array {
        $allSpecies = array();
        $data = (array) $this->getAll();
        foreach ($data as $elem) {
            $rowData = (array) $elem;
            $id = $rowData[$this->primaryKey];
            unset($rowData[$this->primaryKey], $elem);
            $species = new Species(...$rowData);
            $species->setId($id);  
            array_push($allSpecies, $species);
        }
        return $allSpecies;
    }

    // CRUD UPDATE: met à jour l'espece dans la BDD
    public function update($species): bool
    {
        $data = $this->getAllData($species);
        return $this->updateOne($species->getId(), $data);
    }

    // CRUD DELETE: supprime la chauve-souris dans la BDD
    public function delete($species) : bool {
        return $this->deleteOne($species->getId());
    }

}