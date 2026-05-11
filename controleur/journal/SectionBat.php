<?php

namespace controleur\journal;

use app\util\Request as req;
use app\util\BaseURL as url;

use DateTime;
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
        date_default_timezone_set("Europe/Paris");

        $urlAdd = url::getBaseUrl() . "sectionBatAddition";
        $urlModif = url::getBaseUrl() . "sectionBatAddition?bat=mod";
        $urlDelete = url::getBaseUrl() . "sectionBat?bat=del";
        $bat = null;

        $edit = req::get("edition") == "true";
        $section = null;
        $sectionSpecimen = null;

        // Si il faut supprimer une Bat
        if (req::get("bat") == "del") {
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
            $now = new DateTime();
            // Création de l'objet Section
            $section = new Section(
                req::post("sectionTitle"),
                req::post("sectionObservation"),
                req::post("date"),
                $now->format("Y-m-d H:i:s"),
                SessionLogin::getUserId()
            );

            $sectionDAO = new SectionDAO();
            $sectionSpecimenDAO = new SectionSpecimenDAO();

            if (req::has("section")) {
                $sectionId = req::get("id");
                $section->setId($sectionId);
                $sectionSpecimen = $sectionSpecimenDAO->findSpecimenSectionByIdSection($sectionId);
                $sectionSpecimen->setIdBat(req::post("batSelected"));
                $section->updateSection();
                $sectionSpecimen->updateSectionSpecimen();
            } else {
                $section->addSection();
                $sectionBat = new SectionSpecimen($section->getId(), (int) req::post("batSelected"));
                $sectionBat->addSectionSpecimen();
            }
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
}
