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

/**
 * Contrôleur : formulaire de saisie d'une fiche d'observation individu (chauve-souris).
 *
 * Une fiche individu est composée de deux enregistrements liés :
 *   - Section       : titre, contenu, date d'événement, auteur.
 *   - SectionSpecimen : liaison entre la Section et le Bat sélectionné.
 *
 * Ce contrôleur gère trois cas selon les paramètres GET/POST :
 *   ?bat=del&id=X    → supprime le Bat (individu) identifié par X.
 *   ?edition=true&id=X → pré-charge la fiche X pour modification.
 *   POST (sectionTitle + sectionObservation présents)
 *       - ?section=X → mise à jour de la fiche existante.
 *       - (aucun)    → création d'une nouvelle fiche.
 *
 * La gestion de la liste des individus (ajout/modification) est déléguée
 * au contrôleur SectionBatAddition (ROLE_NATURALISTE requis).
 */
class SectionBat
{

    public function __construct()
    {
        Guard::requireRole(ROLE_ADHERENT);

        // Nécessaire pour que les dates affichées correspondent au fuseau local
        date_default_timezone_set("Europe/Paris");

        $urlAdd    = url::getBaseUrl() . "sectionBatAddition";
        $urlModif  = url::getBaseUrl() . "sectionBatAddition?bat=mod";
        $urlDelete = url::getBaseUrl() . "sectionBat?bat=del";
        $bat       = null;

        $edit            = req::get("edition") == "true";
        $section         = null;
        $sectionSpecimen = null;

        // Suppression d'un individu (Bat) depuis la liste
        if (req::get("bat") == "del") {
            $batId = req::get("id");
            $batDAO = new BatDAO();
            $bat = $batDAO->getBatById((int) $batId);
            $bat->deleteBat();
        }

        // Pré-chargement de la fiche et de sa liaison SectionSpecimen pour pré-remplir le formulaire
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

        // Soumission du formulaire : présence de sectionTitle + sectionObservation détecte un POST valide
        if (
            req::has("sectionTitle") &&
            req::has("sectionObservation")
        ) {
            $now = new DateTime();
            // modifDate = date/heure serveur au moment de l'enregistrement (mise à jour à chaque édit)
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
                // Mise à jour : ?section est passé en GET pour indiquer qu'il s'agit d'une édition
                $sectionId = req::get("id");
                $section->setId($sectionId);
                $sectionSpecimen = $sectionSpecimenDAO->findSpecimenSectionByIdSection($sectionId);
                $sectionSpecimen->setIdBat(req::post("batSelected"));
                $section->updateSection();
                $sectionSpecimen->updateSectionSpecimen();
            } else {
                // Création : on insère d'abord Section pour obtenir son ID auto-incrémenté,
                // puis SectionSpecimen qui référence cet ID.
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
