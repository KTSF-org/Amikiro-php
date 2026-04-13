<?php

namespace modele\DAO;

use modele\DAO\base\Database;
use PDO;

/**
 * DAO : Abonnement
 * Gère les accès en base pour la table Abonnement.
 * Chaque ligne représente une période d'abonnement (startDate → endDate) liée à un utilisateur.
 *
 * Un abonnement est considéré actif si endDate >= date du jour (CURDATE()).
 * Plusieurs abonnements peuvent coexister pour un même utilisateur (historique).
 *
 * Associé aux contrôleurs : controleur/admin/UtilisateursEditer.php
 * Utilisé lors de la connexion : controleur/Login.php
 */
class AbonnementDAO extends Database {

    public function __construct() {
        parent::__construct('Abonnement', 'id');
    }

    /**
     * Retourne l'abonnement actif d'un utilisateur, ou false si aucun.
     * Un abonnement est actif si sa date de fin est supérieure ou égale à aujourd'hui.
     * En cas de chevauchements, retourne celui dont la date de fin est la plus éloignée.
     *
     * @param int $idUser Identifiant de l'utilisateur.
     * @return \stdClass|null|bool Abonnement actif ou false.
     */
    public function getActiveByUser(int $idUser): \stdClass|null|bool {
        $stmt = $this->getPdo()->prepare(
            "SELECT * FROM `Abonnement` WHERE idUser = ? AND endDate >= CURDATE() ORDER BY endDate DESC LIMIT 1"
        );
        $stmt->execute([$idUser]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Retourne l'historique complet des abonnements d'un utilisateur,
     * du plus récent au plus ancien.
     *
     * @param int $idUser Identifiant de l'utilisateur.
     * @return array Tableau d'objets stdClass.
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
     * N'invalide pas les abonnements existants — plusieurs périodes peuvent coexister.
     *
     * @param int    $idUser    Identifiant de l'utilisateur.
     * @param string $startDate Date de début au format YYYY-MM-DD.
     * @param string $endDate   Date de fin au format YYYY-MM-DD.
     * @return bool Résultat de l'insertion.
     */
    public function createForUser(int $idUser, string $startDate, string $endDate): bool {
        return $this->createOne([
            'idUser'    => $idUser,
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ]);
    }
}
