<?php

namespace modele\DAO\journalDAO;

use modele\DAO\base\Database;
use app\util\Error;
use modele\journal\Bat;
use PDO;
use UnexpectedValueException;

/**
 * DAO : individus chauves-souris (table Bat).
 *
 * Fournit le CRUD complet pour les objets métier Bat.
 * Hérite des méthodes génériques de Database (getAll, getOne, createOne, etc.).
 *
 * getAllData() : méthode privée qui reflète dynamiquement les getters de Bat
 * pour construire le tableau [colonne => valeur] attendu par createOne/updateOne.
 * Ce pattern est identique dans tous les DAOs de l'application.
 */
class BatDAO extends Database
{
    public function __construct()
    {
        $tableName = "Bat";
        $primaryKey = "id";
        parent::__construct($tableName, $primaryKey);
    }


    /**
     * Construit le tableau [colonne => valeur] à partir des getters de l'objet Bat.
     * getParam() retourne la liste des noms de propriétés déclarée dans le constructeur de Bat.
     * Pour chaque propriété "foo", on appelle getFoo() dynamiquement via ucfirst().
     */
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
    public function getAllBat(): array {
        $allBats = array();
        $data = (array) $this->getAll();
        foreach ($data as $elem) {
            $rowData = (array) $elem;
            $id = $rowData[$this->primaryKey];
            unset($rowData[$this->primaryKey], $elem);
            $bat = new Bat(...$rowData);
            $bat->setId($id);  
            array_push($allBats, $bat);
        }
        return $allBats;
    }


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
