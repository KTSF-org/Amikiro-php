<?php

namespace modele\DAO;

use modele\DAO\base\Database;
use PDO;

/**
 * DAO : périodes d'accès utilisateur (table Abonnement).
 *
 * Chaque ligne représente une fenêtre d'accès (startDate → endDate) liée à un utilisateur.
 * Plusieurs périodes peuvent coexister pour le même utilisateur (historique conservé).
 * Une période est considérée active si endDate >= date du jour.
 */
class SubscriptionDAO extends Database {

    public function __construct() {
        parent::__construct('Abonnement', 'id');
    }

    /**
     * Retourne la période d'accès active d'un utilisateur, ou false si aucune.
     * En cas de chevauchement, retourne celle dont la date de fin est la plus éloignée.
     */
    public function getActiveByUser(int $userId): \stdClass|null|bool {
        $stmt = $this->getPdo()->prepare(
            "SELECT * FROM `Abonnement` WHERE idUser = ? AND endDate >= CURDATE() ORDER BY endDate DESC LIMIT 1"
        );
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Retourne l'historique complet des périodes d'accès d'un utilisateur,
     * de la plus récente à la plus ancienne.
     */
    public function getAllByUser(int $userId): array {
        $stmt = $this->getPdo()->prepare(
            "SELECT * FROM `Abonnement` WHERE idUser = ? ORDER BY endDate DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Crée une nouvelle période d'accès pour un utilisateur.
     * N'invalide pas les périodes existantes.
     *
     * @param int    $userId    Identifiant de l'utilisateur.
     * @param string $startDate Date de début (YYYY-MM-DD).
     * @param string $endDate   Date de fin (YYYY-MM-DD).
     */
    public function createForUser(int $userId, string $startDate, string $endDate): bool {
        return $this->createOne([
            'idUser'    => $userId,
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ]);
    }
}
