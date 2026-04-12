<?php

namespace modele\DAO;

use modele\DAO\base\Database;
use PDO;

class AbonnementDAO extends Database {

    public function __construct() {
        parent::__construct('Abonnement', 'id');
    }

    /**
     * Retourne l'abonnement actif d'un utilisateur (endDate >= aujourd'hui), ou false.
     */
    public function getActiveByUser(int $idUser): \stdClass|null|bool {
        $stmt = $this->getPdo()->prepare(
            "SELECT * FROM `Abonnement` WHERE idUser = ? AND endDate >= CURDATE() ORDER BY endDate DESC LIMIT 1"
        );
        $stmt->execute([$idUser]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Retourne l'historique complet des abonnements d'un utilisateur.
     * @return array
     */
    public function getAllByUser(int $idUser): array {
        $stmt = $this->getPdo()->prepare(
            "SELECT * FROM `Abonnement` WHERE idUser = ? ORDER BY endDate DESC"
        );
        $stmt->execute([$idUser]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Crée un nouvel abonnement pour un utilisateur.
     */
    public function createForUser(int $idUser, string $startDate, string $endDate): bool {
        return $this->createOne([
            'idUser'    => $idUser,
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ]);
    }
}
