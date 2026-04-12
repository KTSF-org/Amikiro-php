<?php

namespace modele\DAO;

use modele\DAO\base\Database;

/**
 *  ConfigDAO
 *  Accès à la table Config (ligne unique, id=1).
 */
class ConfigDAO extends Database {

    public function __construct() {
        parent::__construct('Config', 'id');
    }

    public function getConfig(): \stdClass|null|bool {
        return $this->getOne(1);
    }

    public function updateConfig(array $data): bool {
        return $this->updateOne(1, $data);
    }
}
