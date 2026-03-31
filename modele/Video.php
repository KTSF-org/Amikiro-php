<?php

namespace modele;

class Video
{
    private int $id=0;
    protected $param =[];





    public function __construct(
        private string $title="",
        private string $openDate="",
        private string $closeDate="",
        private string $url="")
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
        return $this->url;
    }


    /**
     * SETTERS
     */

    public function setIdVideo(int $idVideo): void
    {
        $this->idVideo = $idVideo;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setOpenDate(string $openDate): void
    {
        $this->openDate = $openDate;
    }

    public function setCloseDate(string $closeDate): void
    {
        $this->closeDate = $closeDate;
    }

    public function setURL(string $url): void
    {
        $this->url = $url;
    }






}