<?php
namespace modele\journal;
use app\util\Error;
use modele\DAO\journalDAO\SectionColonyDAO;



/**
 * Modèle métier : liaison entre une fiche colonie et sa catégorie (table ColonySection).
 *
 * SectionColony est la table de jonction entre Section et Category.
 * Elle indique que la fiche $idSection est une observation de type $idCategory.
 *
 * Une Section ne peut être liée qu'à une seule ColonySection (relation 1-1).
 * Si une Section a une ColonySection, elle n'a pas de SectionSpecimen, et vice-versa.
 */
class SectionColony
{

    private int $id = 0;
    protected $param = []; // Liste des noms d'attributs pour SectionColonyDAO::getAllData()

   
    public function __construct(
        private int $idSection = -1,
        private int $idCategory = -1 
    ) 
    {


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

    // METHODS
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
    public function addSectionColony(): bool
    {
        $sectionColonyDAO = new SectionColonyDAO();
        return $sectionColonyDAO->create($this);
    }

    // UPDATE : Met à jour la sectionColony dans la BDD
    public function updateSectionColony(): bool
    {
        $sectionColonyDAO = new SectionColonyDAO();
        return $sectionColonyDAO->update($this);
    }

    // DELETE
    public function deleteSectionColony(): bool
    {
        $sectionColonyDAO = new SectionColonyDAO();
        return $sectionColonyDAO->delete($this);
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

    public function getIdCategory()
    {
        return $this->idCategory;
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

    public function setIdCategory(int $idCategory): void
    {
        $this->idCategory = $idCategory;
    }
}