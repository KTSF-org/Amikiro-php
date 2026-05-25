<?php
namespace modele\DAO\journalDAO;

use modele\DAO\base\Database;
use app\util\Error;
use modele\journal\SectionColony;
use PDO;

/**
 * DAO : liaisons entre fiches colonie et catégories (table ColonySection).
 *
 * Chaque ligne associe une Section (fiche d'observation) à une Category.
 * Une Section ne peut avoir qu'une seule entrée dans ColonySection.
 *
 * Méthode clé :
 *   findColonySectionByIdSection($idSection) → retourne l'objet SectionColony lié,
 *   ou null si la fiche n'est pas de type colonie. Utilisée pour distinguer
 *   les fiches colonie des fiches individu (SectionSpecimenDAO).
 */
class SectionColonyDAO extends Database
{
    public function __construct()
    {
        parent::__construct('ColonySection', 'id');
    }

    private function getAllData($sectionColony): array
    {
        $data = [];
        $keys = $sectionColony->getParam();

        foreach ($keys as $key) {
            $methodName = "get" . ucfirst($key);
            if (method_exists($sectionColony, $methodName)) {
                $data[$key] = $sectionColony->$methodName();
            } else {
                $data[$key] = null;
            }
        }
        return $data;
    }


    // CREATE
    public function create($sectionColony): bool
    {
        $data = $this->getAllData($sectionColony);
        $bool = $this->createOne($data);
        $sectionColony->setId($this->getLastKey());
        return $bool;
    }

    // READ : Retourne une SectionColony depuis la BDD grâce à un ID
    public function getSectionColony(int $id): mixed
    {
        $row = false;
        if ($id > 0)
            $row = $this->getOne($id);
        if (!$row)
            Error::setException("l'indentifiant fourni (<b>$id</b>) est invalide !");
        $rowData = (array) $row;
        unset($rowData[$this->primaryKey], $row);
        $sectionColony = new SectionColony(...$rowData);
        $sectionColony->setId($id);
        return $sectionColony;
    }

    // READ : Retourne toutes les SectionColony
    public function getAllSectionColony(): array
    {
        $allSectionColony = array();
        $data = (array) $this->getAll();
        foreach ($data as $elem) {
            $rowData = (array) $elem;
            $id = $rowData[$this->primaryKey];
            unset($rowData[$this->primaryKey], $elem);
            $sectionColony = new SectionColony(...$rowData);
            $sectionColony->setId($id);
            array_push($allSectionColony, $sectionColony);
        }
        return $allSectionColony;
    }

    // CRUD UPDATE : met à jour la sectionColony dans la BDD
    public function update($sectionColony): bool
    {
        $data = $this->getAllData($sectionColony);
        return $this->updateOne($sectionColony->getId(), $data);
    }

    // CRUD DELETE : supprime une sectionColony dans la BDD
    public function delete($sectionColony): bool
    {
        return $this->deleteOne($sectionColony->getId());
    }

    /**
     * Retourne le SectionColony associé à une fiche, ou null si inexistant.
     * Utilisée dans Journal et SectionRead pour identifier le type de fiche.
     * On supprime la clé 'id' avant le spread car SectionColony ne l'attend pas
     * dans son constructeur (elle est gérée séparément via setId()).
     */
    public function findColonySectionByIdSection($idSection): mixed
    {
        $stmt = $this->getPdo()->prepare("SELECT * FROM `" . $this->tableName . "` WHERE idSection = :idSection");
        $stmt->execute([':idSection' => "$idSection"]);
        $section = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($section != null) {
            unset($section['id']);
            return new SectionColony(...$section);
        }
        return null;
    }
}







