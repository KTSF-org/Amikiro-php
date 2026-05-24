<?php

namespace modele\DAO;

use modele\DAO\base\Database;

/**
 * DAO : Config
 * Gère la configuration globale de l'application (table Config, ligne unique id=1).
 *
 * Contrairement aux autres DAOs, Config ne dispose pas d'objet métier associé :
 * la table ne contient qu'une seule ligne et les valeurs sont lues/écrites directement.
 *
 * Paramètres stockés :
 *   - streamUrl       : URL du flux webcam.
 *   - sessionDuration : durée de session par défaut en secondes.
 *   - viewerLimit     : nombre maximum de viewers simultanés par session.
 *
 * Utilisé par : controleur/admin/Webcam.php
 */
class ConfigDAO extends Database {

    public function __construct() {
        parent::__construct('Config', 'id');
    }

    /**
     * Retourne la ligne de configuration (id=1).
     *
     * @return \stdClass|null|bool Configuration courante ou false en cas d'erreur.
     */
    public function getConfig(): \stdClass|null|bool {
        return $this->getOne(1);
    }

    /**
     * Met à jour la configuration existante (id=1).
     *
     * @param array $data Tableau associatif des colonnes à mettre à jour.
     * @return bool Résultat de la mise à jour.
     */
    public function updateConfig(array $data): bool {
        return $this->updateOne(1, $data);
    }

    public function getURLbyId(int $id): mixed {
        return $this->sendSQLAssoc("SELECT * from `" . $this->tableName . "` WHERE id = ?", [$id]);
    }

    /**
     * Incrémente le compteur de viewers actifs.
     */
    public function incrementViewers(): void {
        $this->getPdo()->exec("UPDATE `Config` SET viewerCount = viewerCount + 1 WHERE id = 1");
    }

    /**
     * Décrémente le compteur de viewers actifs (plancher à 0).
     */
    public function decrementViewers(): void {
        // GREATEST(0, ...) empêche le compteur de passer en négatif si decrementViewers est appelé en double
        $this->getPdo()->exec("UPDATE `Config` SET viewerCount = GREATEST(0, viewerCount - 1) WHERE id = 1");
    }
}
