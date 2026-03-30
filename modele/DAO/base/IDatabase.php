<?php

namespace modele\DAO\base;

/**
 * Interface IDatabase
 * @package modele\DAO
 * @property string $tableName
 * @property string $primaryKey
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
