<?php

namespace modele\DAO;

use modele\DAO\base\Database;
use PDO;

class SubscriptionDAO extends Database {

    public function __construct() {
        parent::__construct('Abonnement', 'id');
    }

    public function getActiveByUser(int $userId): \stdClass|null|bool {
        $stmt = $this->getPdo()->prepare(
            "SELECT * FROM `Abonnement` WHERE idUser = ? AND endDate >= CURDATE() ORDER BY endDate DESC LIMIT 1"
        );
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAllByUser(int $userId): array {
        $stmt = $this->getPdo()->prepare(
            "SELECT * FROM `Abonnement` WHERE idUser = ? ORDER BY endDate DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function createForUser(int $userId, string $startDate, string $endDate): bool {
        return $this->createOne([
            'idUser'    => $userId,
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ]);
    }
}
