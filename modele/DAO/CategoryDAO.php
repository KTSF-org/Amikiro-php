<?php

namespace modele\DAO;

use modele\DAO\base\Database;
use PDO;

/**
 * CategoryDAO — Accès aux données de la table Category
 *
 * Gère les types d'événements observables (Mise-bas, Départ chasse, etc.).
 * Utilisé par ColonyJournal pour peupler les listes déroulantes.
 */
class CategoryDAO extends Database {

    public function __construct() {
        parent::__construct('Category', 'IdCategory');
    }

    /**
     * Retourne toutes les catégories triées par nom.
     * @return \stdClass[]
     */
    public function findAll(): array {
        try {
            $stmt = $this->getPdo()->prepare(
                "SELECT IdCategory, Name FROM `Category` ORDER BY Name ASC"
            );
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ) ?: [];
        } catch (\PDOException $e) {
            error_log('[CategoryDAO::findAll] ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Retourne une catégorie par son IdCatergory.
     * @param int $id
     * @return \stdClass|null
     */
    public function findById(int $id): ?\stdClass {
        try {
            $row = $this->getOne((string)$id);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[CategoryDAO::findById] ' . $e->getMessage());
            return null;
        }
    }
}
