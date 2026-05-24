<?php

namespace modele\DAO\base;

/**
 * Interface IDatabase
 * @package modele\DAO
 * @property string $tableName
 * @property string $primaryKey
 */
/**
 * Contrat des opérations CRUD implémentées par Database.
 *
 * Toute classe DAO concrète hérite de Database, qui implémente cette interface.
 * L'interface sert de documentation du contrat minimal attendu de tout DAO.
 *
 * Nota : deleteOne() et deleteMany() acceptent $disableConstraintKey (true = désactive FK checks).
 * Ce paramètre est NON FONCTIONNEL car PDO ne supporte pas les multi-instructions dans exec().
 * Ne pas l'utiliser — écrire une méthode DAO dédiée à la place.
 */
interface IDatabase {

	public function getLastKey();

    public function sendSQL(string $cmd, array $filter);
	
    public function getAll();

    public function getOne(string $id);
	
	public function createOne(array $data);
	
	public function updateOne(string $id, array $data);

    public function deleteOne(string $id, bool $disableConstraintKey);
	
    public function deleteMany(array $id, bool $disableConstraintKey);

}
