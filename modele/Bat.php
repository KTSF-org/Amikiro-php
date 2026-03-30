<?php

namespace modele;
use app\util\Error;
use DateTime;
use modele\DAO\BatDAO;
use mysqli_sql_exception;

class Bat
{

    private int $idBat = 1;
    protected $param = []; //La liste des paramètres (ou attributs)

    private string $name;
    private string $birthDate;
    private int $sex;
    private int $weight;
    private string $note;

    // Constructeur : Bat
    public function __construct() {
        $name = "";
        $birthDate = "";
        $sex = -1;
        $weight = -1;
        $note = "";
    
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
            if ($key === "idBat" or $key === "param")
                continue;
            $param[] = $key;
        }
        return $param;
    }

    public function getBat() : void {
        $batDAO = new BatDAO();
        $data = $batDAO->getBatById(1);
        var_dump($data);
    }

    /** 
     * GETTERS
     */

    public function getIdBat(): int
    {
        return $this->idBat;
    }

    public function getName(): string
    {
        return $this->name;
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

    public function setIdBat(int $idBat): void
    {
        $this->idBat = $idBat;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
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