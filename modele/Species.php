<?php

namespace modele;
use app\util\Error;
use DateTime;
use modele\DAO\SpeciesDAO;
use mysqli_sql_exception;

class Bat
{
    private int $id = 0;
    protected $param = [];

    // Constructeur : Species
    public function __construct(
        private string $scientificName = "",
        private string $commonName = ""
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

    /** 
     * GETTERS
     */

    public function getId(): int
    {
        return $this->id;
    }

        public function getScientificName(): string
    {
        return $this->scientificName;
    }

        public function getCommonName(): string
    {
        return $this->commonName;
    }

    /**
     *  SETTERS
     */

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setScientificName(string $scientificName): void
    {
        $this->scientificName = $scientificName;
    }

    public function setCommonName(string $commonName): void
    {
        $this->commonName = $commonName;
    }
}