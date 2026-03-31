<?php

namespace modele\DAO;

use modele\DAO\base\Database;
use modele\Video;
use PDO;

class VideoDAO extends Database
{


    /**
     *	Deux paramètres pour le constructeur du DAO :
     *	1/ nom de la table
     *	2/ nom de la clé primaire
     *	Voir les méthodes du CRUD dans le DAO (modele/DAO/base/Database.php).
     */


    public function __construct()
    {
        $tableName = "Video";
        $primaryKey = "IdVideo";

        parent::__construct($tableName, $primaryKey);
    }

    private function getAllData($video): array {
        $data =[];
        $keys = $video->getParam();


        foreach ($keys as $key) {
            $methodName = 'get' . ucfirst($key);
            if (method_exists($video, $methodName)) {
                $data[$key] = $video->$methodName();
            } else {
                $data[$key] = null;
            }
        }return $data;
    }


    /**
     *	CRUD : create
     *	@param object:metier Instance de l'objet métier
     *	@return bool
     */

    public function create($video): bool{
        $data = $this->getAllData($video);
        //createOne() et getLastKey() sont des méthodes du DAO (modele/DAO/base/Database.php)
        $bool = $this->create($data);
        $video->setId($this->getLastKey());
        return $bool;
    }

    /**
     *	CRUD : read
     *	@param integer Numéro de la clé primaire
     *	@return mixed object|string|bool
     */

    public function read(int $IdVideo=1): mixed {
        $row = false;
        if($id>0)$row = $this->getOne($id); //on récupère la ligne/tuple concernée
        //gestion de l'index en cas d'erreur :
        if(!$row) {
            Error::setException( "l'indentifiant fourni (<b>$id</b>) est invalide !" );
        }
        $rowData = (array)$row; //conversion objet --> array
        unset($rowData[$this->primaryKey], $row); //retire la clé primaire du tableau et $row qui ne sert plus
        $video = new Video(...$rowData); //crée l'objet Video(->Video.php) avec toutes les clés du tableau $rowData
        $video->setId($id); //ajoute $id dans l'objet métier (Video)
        return $video; //retourne l'objet crée
    }

    /**
     *	CRUD : update
     *	@param object:metier Instance de l'objet métier
     *	@return bool
     */
    public function update($video): bool {
        $data = $this->getAllData($video);
        //updateOne() est une méthode du DAO (modele/DAO/base/Database.php)
        return $this->updateOne($video->getId(), $data);
    }

    /**
     *	CRUD : delete
     *	@param object:metier Instance de l'objet métier
     *	@return bool
     */
    public function delete($video): bool {
        //deleteOne() est une méthode du DAO (modele/DAO/base/Database.php)
        return $this->deleteOne( $video->getId() );
    }


    public function getURLbyId(int $IdVideo): mixed {
        $stmt= $this->getPdo()->prepare("SELECT URL FROM Video WHERE IdVideo = :IdVideo");
        $stmt->execute(["IdVideo"=>$IdVideo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}