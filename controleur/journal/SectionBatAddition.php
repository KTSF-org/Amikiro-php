<?php

namespace controleur\journal;

use app\util\Request as req;
use app\util\BaseURL as url;

use modele\DAO\journalDAO\BatDAO;
use modele\DAO\journalDAO\SectionDAO;
use modele\DAO\journalDAO\SectionSpecimenDAO;
use modele\journal\Section;
use modele\journal\SectionSpecimen;
use vue\base\MainTemplate as Vue;
use app\util\Guard;
use app\util\SessionLogin;

use modele\journal\Bat;
use modele\DAO\journalDAO\SpeciesDAO;

class SectionBatAddition
{

	public function __construct()
	{
		$bat = null;

		$speciesDAO = new SpeciesDAO();
		$allSpecies = $speciesDAO->getAllSpecies();

		// Si le formulaire est remplis
		if (
			req::has("batName") &&
			req::has("batWeight") &&
			req::has("batNotes")
		) {
			// Création de l'objet Bat
			$bat = new Bat(
				req::post("batName"),
				(int) req::post("batSpecies"),
				req::post("batBirthDate"),
				(int) req::post("batSex"),
				req::post("batWeight"),
				req::post("batNotes")
			);

			// Si c'est un ajout, ajoute la Bat dans la BDD,
			// sinon, met à jour la Bat dans la BDD
			if (req::get("bat") == "add") {
				$bat->addBat();
			} else {
				$bat->setId(req::get("id"));
				$bat->updateBat();
			}
		}

		// Si c'est une modification de la chauve-souris :
		// - transmet à la vue que c'est un modification ou non
		// - transmet l'objet Bat à la vue pour pré-remplir les champs
		$modif = req::get("bat") == "mod";
		if ($modif) {
			$batId = req::get("id");
			$batDAO = new BatDAO();
			$bat = $batDAO->getBatById((int) $batId);
		}

		Vue::render(
			'journal/SectionBatAddition',
			[
				"bat" => $bat,
				"modif" => $modif,
				"allSpecies" => $allSpecies
			]
		);

	}
}
