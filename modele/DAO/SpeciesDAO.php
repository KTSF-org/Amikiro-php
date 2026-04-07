<?php

namespace modele\DAO;

use modele\DAO\base\Database;
use app\util\Error;
use modele\Species;
use PDO;
use UnexpectedValueException;

class BatDAO extends Database
{

    public function __construct()
    {
        $tableName = "Species";
        $primaryKey = "id";
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

}