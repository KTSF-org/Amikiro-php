<?php

namespace modele\DAO;

use modele\DAO\base\Database;
use app\util\Error;
use modele\Bat;
use PDO;

class BatDAO extends Database
{
    /** 
     *	Deux paramètres pour le constructeur du DAO :
     *	1/ nom de la table
     *	2/ nom de la clé primaire
     *	Voir les méthodes du CRUD dans le DAO (modele/DAO/base/Database.php).
     */
    public function __construct()
    {
        $tableName = "Bat";
        $primaryKey = "IdBat";
        parent::__construct($tableName, $primaryKey);
    }


    private function getAllData($bat): array
    {
        $data = [];
        $keys = $bat->getParam();

        foreach ($keys as $key) {
            $methodName = "get" . ucfirst($key);
            if (method_exists($bat, $methodName)) {
                $data[$key] = $bat->$methodName();
            } else {
                $data[$key] = null;
            }
        }
        return $data;
    }

    // Pour les tests
    public function getBatById(int $id) : mixed {
        return $this->sendSQL(
            "SELECT * FROM ". $this->tableName . " WHERE IdBat = ?", [$id]
        );
    }


}
