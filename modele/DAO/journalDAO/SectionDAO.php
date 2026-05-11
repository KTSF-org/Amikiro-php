<?php

namespace modele\DAO\journalDAO;

use modele\DAO\base\Database;
use modele\journal\Section;
use app\util\Error;
use PDO;

class SectionDAO extends Database {

    public function __construct() {
        parent::__construct('Section', 'id');
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

	public function find(int $id): mixed {
		$row = false;
		if ($id > 0) $row = $this->getOne($id);
		if (!$row) {
			Error::setException("l'identifiant fourni (<b>$id</b>) est invalide !");
		}
		$rowData = (array)$row;
		unset($rowData[$this->primaryKey], $row);
		$metier = new Section(...$rowData);
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

    // public function findAll(): array {
    //     try {
    //         $stmt = $this->getPdo()->prepare(
    //             "SELECT id, title FROM `Section` ORDER BY creationDate DESC"
    //         );
    //         $stmt->execute();
    //         return $stmt->fetchAll(PDO::FETCH_OBJ) ?: [];
    //     } catch (\PDOException $e) {
    //         error_log('[SectionDAO::findAll] ' . $e->getMessage());
    //         return [];
    //     }
    // }
	public function findAll(): array {
        $allSection = array();
        $data = (array) $this->getAll();
        foreach ($data as $elem) {
            $rowData = (array) $elem;
            $id = $rowData[$this->primaryKey];
            unset($rowData[$this->primaryKey], $elem);
            $section = new Section(...$rowData);
            $section->setId($id);
            array_push($allSection, $section);
        }
        return $allSection;
    }

	public function findAllByAuth($idUser): array {
        $allSection = $this->findAll();
		$userSection = [];
		foreach($allSection as $sec) {
			if ($sec->getIdUser() == $idUser) {
				array_push($userSection, $sec);
			}
		}
		return $userSection;
    }
}
