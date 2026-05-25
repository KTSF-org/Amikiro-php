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

/**
 * Contrôleur : ajout ou modification d'un individu chauve-souris (Bat).
 *
 * Un Bat est un animal identifié (nom, espèce, date de naissance, sexe, poids).
 * Il est distinct d'une fiche d'observation (Section) : un même Bat peut
 * apparaître dans plusieurs fiches SectionBat.
 *
 * Ce contrôleur gère deux cas selon le paramètre GET ?bat :
 *   ?bat=add → création d'un nouveau Bat après soumission du formulaire.
 *   ?bat=mod&id=X → modification du Bat identifié par X.
 *
 * Accès restreint à ROLE_NATURALISTE : seuls les naturalistes peuvent
 * créer ou modifier des individus dans la base.
 */
class SectionBatAddition
{

	public function __construct()
	{
		Guard::requireRole(ROLE_NATURALISTE);

		$bat = null;

		// Charge les espèces pour le menu déroulant du formulaire
		$speciesDAO = new SpeciesDAO();
		$allSpecies = $speciesDAO->getAllSpecies();

		// Soumission du formulaire : les trois champs obligatoires sont présents
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

			// ?bat=add → insertion, tout autre valeur (ex: "mod") → mise à jour
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
