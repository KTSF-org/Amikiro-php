<?php

namespace modele\DAO\journalDAO;

use modele\DAO\base\Database;
use modele\journal\SectionSpecimen;
use modele\journal\Section;
use app\util\Error;
use PDO;

class SectionSpecimenDAO extends Database
{

    public function __construct()
    {
        $tableName = "SpecimenSection";
        $primaryKey = "id";
        parent::__construct($tableName, $primaryKey);
    }


    private function getAllData($sectionSpecimen): array
    {
        $data = [];
        $keys = $sectionSpecimen->getParam();

        foreach ($keys as $key) {
            $methodName = "get" . ucfirst($key);
            if (method_exists($sectionSpecimen, $methodName)) {
                $data[$key] = $sectionSpecimen->$methodName();
            } else {
                $data[$key] = null;
            }
        }
        return $data;
    }


    // CREATE
    public function create($sectionSpecimen): bool
    {
        $data = $this->getAllData($sectionSpecimen);
        $bool = $this->createOne($data);
        $sectionSpecimen->setId($this->getLastKey());
        return $bool;
    }



    // READ : Retourne une SectionSpecimen depuis la BDD grâce à un ID
    public function getSectionSpecimenById(int $id): mixed
    {
        $row = false;
        if ($id > 0)
            $row = $this->getOne($id);
        if (!$row)
            Error::setException("l'indentifiant fourni (<b>$id</b>) est invalide !");
        $rowData = (array) $row;
        unset($rowData[$this->primaryKey], $row);
        $sectionSpecimen = new SectionSpecimen(...$rowData);
        $sectionSpecimen->setId($id);
        return $sectionSpecimen;
    }


    // READ : Retourne toutes les SectionSpecimen
    public function getAllSectionSpecimen(): array
    {
        $allSectionSpecimen = array();
        $data = (array) $this->getAll();
        foreach ($data as $elem) {
            $rowData = (array) $elem;
            $id = $rowData[$this->primaryKey];
            unset($rowData[$this->primaryKey], $elem);
            $sectionSpecimen = new SectionSpecimen(...$rowData);
            $sectionSpecimen->setId($id);
            array_push($allSectionSpecimen, $sectionSpecimen);
        }
        return $allSectionSpecimen;
    }

    // CRUD UPDATE : met à jour la sectionSpecimen dans la BDD
    public function update($sectionSpecimen): bool
    {
        $data = $this->getAllData($sectionSpecimen);
        return $this->updateOne($sectionSpecimen->getId(), $data);
    }

    // CRUD DELETE : supprime une sectionSpecimen dans la BDD
    public function delete($sectionSpecimen): bool {
        return $this->deleteOne($sectionSpecimen->getId());
    }
}