<?php

namespace modele\journal;
use app\util\Error;
use DateTime;
use modele\DAO\journalDAO\BatDAO;
use modele\journal\Species;
use mysqli_sql_exception;

class Bat
{

    private int $id=0;
    protected $param = []; //La liste des paramètres (ou attributs)

    // Constructeur : Bat
    public function __construct(
        private string $name = "",
        private int $idSpecies = -1,
        private string $birthDate = "",
        private int $sex = -1,
        private int $weight = -1,
        private string $note = ""
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

    // STOCKER LA LISTE DES ATTRIBUTS
    private function getKey(array $arr): array
    {
        foreach ($arr as $key => $value) {
            if ($key === "IdBat" or $key === "param")
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

    // Ajoute la chauve-souris dans la BDD
    public function addBat(): bool
    {
        $batDAO = new BatDAO();
        return $batDAO->create($this);
    }

    // Met à jour la chauve-souris dans la BDD
    public function updateBat(): bool
    {
        $batDAO = new BatDAO();
        return $batDAO->update($this);
    }

    // Supprime la chauve-souris de la BDD
    public function deleteBat() : bool {
        $batDAO = new BatDAO();
        return $batDAO->delete($this);
    }

    /** 
     * GETTERS
     */

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIdSpecies(): int
    {
        return $this->idSpecies;
    }

    public function getBirthDate(): string
    {
        return $this->birthDate;
    }

    public function getSex(): int
    {
        return $this->sex;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    /**
     *  SETTERS
     */

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setIdSpecies(int $idSpecies): void
    {
        $this->idSpecies = $idSpecies;
    }

    public function setBirthDate(string $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function setSex(int $sex): void
    {
        $this->sex = $sex;
    }

    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }

    public function setNote(string $note): void
    {
        $this->note = $note;
    }

}