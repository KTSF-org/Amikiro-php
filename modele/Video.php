<?php

namespace modele;

class Video
{
    private int $IdVideo=0;
    protected $param =[];





    public function __construct(
        private string $Title="",
        private string $OpenDate="",
        private string $CloseDate="",
        private string $URL="")
    {


        //gestionnaire d'erreur (pour les requêtes):
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
    private function getKey(array $arr): array {
        foreach($arr as $key => $value) {
            if($key==="id" or $key==="param")continue;
            $param[] = $key;
        }
        return $param;
    }

    // SORTIR LA LISTE DES ATTRIBUTS
    public function getParam(): array {
        return $this->param;
    }

    // CREATE
    public function addVideo(): bool {
        $videoDAO = new VideoDAO();
        return $videoDAO->create($this);
    }


    /**
     * GETTERS
     */
    public function getIdVideo(): int
    {
        return $this->IdVideo;
    }

    public function getCloseDate(): string
    {
        return $this->CloseDate;
    }

    public function getOpenDate(): string
    {
        return $this->OpenDate;
    }

    public function getTitle(): string
    {
        return $this->Title;
    }

    public function getURL(): string
    {
        return $this->URL;
    }


    /**
     * SETTERS
     */

    public function setIdVideo(int $IdVideo): void
    {
        $this->IdVideo = $IdVideo;
    }

    public function setTitle(string $Title): void
    {
        $this->Title = $Title;
    }

    public function setOpenDate(string $OpenDate): void
    {
        $this->OpenDate = $OpenDate;
    }

    public function setCloseDate(string $CloseDate): void
    {
        $this->CloseDate = $CloseDate;
    }

    public function setURL(string $URL): void
    {
        $this->URL = $URL;
    }






}