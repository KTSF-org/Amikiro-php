<?php

namespace modele\journal;
use app\util\Error;
use modele\DAO\journalDAO\SectionSpecimenDAO;

/**
 * Modèle métier : liaison entre une fiche individu et la chauve-souris observée (table SpecimenSection).
 *
 * SectionSpecimen est la table de jonction entre Section et Bat.
 * Elle indique que la fiche $idSection concerne l'individu $idBat.
 *
 * Une Section ne peut être liée qu'à une seule SectionSpecimen (relation 1-1).
 * Si une Section a une SectionSpecimen, elle n'a pas de ColonySection, et vice-versa.
 */
class SectionSpecimen
{
    private int $id = 0;
    protected $param = []; // Liste des noms d'attributs pour SectionSpecimenDAO::getAllData()

    public function __construct(
        private int $idSection = -1,
        private int $idBat = -1
    ) {

        // Gestionnaire d'erreur (pour les requêtes) :
        try {
            $this->param = $this->getKey(get_object_vars($this));
            Error::checkModelArgs(get_object_vars($this), __CLASS__, func_get_args());
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
    private function getKey(array $arr): array
    {
        foreach ($arr as $key => $value) {
            if ($key === "id" or $key === "param")
                continue;
            $param[] = $key;
        }
        return $param;
    }

    // SORTIR LA LISTE DES ATTRIBUTS
    public function getParam(): array
    {
        return $this->param;
    }



    // CREATE
    public function addSectionSpecimen(): bool
    {
        $sectionSpecimenDAO = new SectionSpecimenDAO();
        return $sectionSpecimenDAO->create($this);

    }

    // Update : Met à jour le sectionSpecimen dans la BDD
    public function updateSectionSpecimen(): bool
    {
        $sectionSpecimenDAO = new SectionSpecimenDAO();
        return $sectionSpecimenDAO->update($this);
    }

    // Delete
    public function deleteSectionSpecimen(): bool
    {
        $sectionSpecimenDAO = new SectionSpecimenDAO();
        return $sectionSpecimenDAO->delete($this);
    }





    // GETTER
    public function getId(): int
    {
        return $this->id;
    }

    public function getIdSection()
    {
        return $this->idSection;
    }

    public function getIdBat()
    {
        return $this->idBat;
    }
    
    // SETTER
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setIdSection(int $idSection): void
    {
        $this->idSection = $idSection;

    }

    public function setIdBat(int $idBat): void
    {
        $this->idBat = $idBat;
    }
}