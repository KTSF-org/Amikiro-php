<?php

namespace modele\journal;
use app\util\Error;
use modele\DAO\SectionSpecimenDAO;

class SectionSpecimen
{

    protected $param = []; //La liste des paramètres (ou attributs)

    public function __construct(
        private int $idSection = 0,
        private int $idBat = 0
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

    //TODO CRUD à faire

}