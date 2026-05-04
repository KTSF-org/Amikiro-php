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

class SectionBat
{

    public function __construct()
    {

        $urlAdd = url::getBaseUrl() . "sectionBat?page=addition";
        $urlModif = url::getBaseUrl() . "sectionBat?page=modification";
        $urlDelete = url::getBaseUrl() . "sectionBat?delete=true";
        $bat = null;

        // Page de la création de fiche chauve-souris
        if (!req::has("page")) {

            $edit = req::get("edition") == "true";
            $section = null;
            $sectionSpecimen = null;

            if (req::get("delete") == "true") {
                $batId = req::get("id");
                $batDAO = new BatDAO();
                $bat = $batDAO->getBatById((int) $batId);
                $bat->deleteBat();
            }
            // Si modification de la fiche chauve-souris
            else if ($edit) {
                $sectionDAO = new SectionDAO();
                $sectionSpecimenDAO = new SectionSpecimenDAO();
                $section = $sectionDAO->find(req::get("id"));
                $sectionSpecimen = $sectionSpecimenDAO->findSpecimenSectionByIdSection(req::get("id"));
            }


            // Tableau associatif, clé : idSpecies, valeur : commonName (de Species).
            $speciesDAO = new SpeciesDAO();
            $speciesAsso = [];
            $speciesList = $speciesDAO->getAllSpecies();
            foreach ($speciesList as $spe) {
                $speciesAsso[$spe->getId()] = $spe->getCommonName();
            }

            // Tableau correspondant au sexes possibles
            $sexList = ["Inconnu", "Femelle", "Mâle"];

            // Liste de toutes les chauve-souris de la BDD
            $batDAO = new BatDAO();
            $batList = $batDAO->getAllBat();

            // Ajoute la fiche individu à la BDD
            if (
                req::has("sectionTitle") &&
                req::has("sectionObservation")
            ) {
                $section = new Section(
                    req::post("sectionTitle"),
                    req::post("sectionObservation"),
                    req::post("date"),
                    SessionLogin::getUserId()
                );
                $section->addSection();
                $sectionBat = new SectionSpecimen($section->getId(), (int) req::post("batSelected"));
                $sectionBat->addSectionSpecimen();
            }

            Vue::render(
                'journal/SectionBat',
                [
                    "urlAdd" => $urlAdd,
                    "urlModif" => $urlModif,
                    "urlDelete" => $urlDelete,
                    "batList" => $batList,
                    "speciesList" => $speciesAsso,
                    "sexList" => $sexList,
                    "edit" => $edit,
                    "section" => $section,
                    "sectionSpecimen" => $sectionSpecimen,
                ]
            );

        }

        // Page de l'ajout ou modification d'une chauve-souris
        else {

            // Création du code html pour la liste des espèces présente dans la BDD
            $speciesDAO = new SpeciesDAO();
            $allSpecies = $speciesDAO->getAllSpecies();

            // TODO
            // Controle du formulaire d'ajout de Bat (si le nom existe déjà,
            // remplir tout le formulaire pour enregistrer dans la bdd ? ect)

            // Si le formulaire est remplis
            if (
                req::has("batName") &&
                req::has("batWeight") &&
                req::has("batNotes")
            ) {
                // Création de la variable du sexe
                switch (req::post("batSex")) {
                    case "unknow":
                        $sex = 0;
                        break;
                    case "female":
                        $sex = 1;
                        break;
                    case "male":
                        $sex = 2;
                        break;
                    default:
                        $sex = 0;
                        break;
                }

                $bat = new Bat(
                    req::post("batName"),
                    (int) req::post("batSpecies"),
                    req::post("batBirthDate"),
                    $sex,
                    req::post("batWeight"),
                    req::post("batNotes")
                );

                if (req::get("page") == "addition") {
                    $bat->addBat();
                } else {
                    $bat->setId(req::get("id"));
                    $bat->updateBat();
                }
            }

            // Si c'est une modification de la chauve-souris
            $modif = req::get("page") == "modification";
            if ($modif) {
                $batId = req::get("id");
                $batDAO = new BatDAO();
                $bat = $batDAO->getBatById((int) $batId);
            }


            Vue::render(
                'journal/SectionBatAddition',
                [
                    "url" => $urlAdd,
                    "urlModif" => $urlModif,
                    "urlDelete" => $urlDelete,
                    "bat" => $bat,
                    "modif" => $modif,
                    "allSpecies" => $allSpecies
                ]
            );
        }
    }
}
