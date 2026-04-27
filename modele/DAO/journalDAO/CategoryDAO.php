<?php

namespace modele\DAO\journalDAO;

use modele\DAO\base\Database;
use modele\journal\Category;
use app\util\Error;
use PDO;

class CategoryDAO extends Database {

    public function __construct() {
        parent::__construct('Category', 'id');
    }

	private function getAllData($metier): array {
		$data = [];
		$keys = $metier->getParam();

		foreach ($keys as $key) {
			$methodName = 'get' . ucfirst($key);
			if (method_exists($metier, $methodName)) {
				$data[$key] = $metier->$methodName();
			} else {
				$data[$key] = null;
			}
		}

		return $data;
	}

	public function create($metier): bool {
		$data = $this->getAllData($metier);
		$bool = $this->createOne($data);
		$metier->setId($this->getLastKey());
		return $bool;
	}

	public function read(int $id = 1): mixed {
		$row = false;
		if ($id > 0) $row = $this->getOne($id);
		if (!$row) {
			Error::setException("l'identifiant fourni (<b>$id</b>) est invalide !");
		}
		$rowData = (array)$row;
		unset($rowData[$this->primaryKey], $row);
		$metier = new Category(...$rowData);
		$metier->setId($id);
		return $metier;
	}

	public function update($metier): bool {
		$data = $this->getAllData($metier);
		return $this->updateOne($metier->getId(), $data);
	}

	public function delete($metier): bool {
		return $this->deleteOne($metier->getId());
	}

    public function findAll(): array {
        try {
            $stmt = $this->getPdo()->prepare(
                "SELECT id, name FROM `Category` ORDER BY id DESC"
            );
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ) ?: [];
        } catch (\PDOException $e) {
            error_log('[CategoryDAO::findAll] ' . $e->getMessage());
            return [];
        }
    }

    public function findById(int $id): ?\stdClass {
        try {
            $row = $this->getOne((string)$id);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[CategoryDAO::findById] ' . $e->getMessage());
            return null;
        }
    }

	public function getAllcategories(): array {
		$allCategories = array();
		$data = (array)$this->getAll();
		foreach ($data as $elem){
			$rowData = (array)$elem;
			$id = $rowData[$this->primaryKey];
			unset($rowData[$this->primaryKey], $elem);
			$category = new Category(...$rowData);
			$category->setId($id);
			array_push($allCategories, $category);
		}
		return $allCategories;
	}
}
