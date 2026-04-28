<?php

namespace controleur\journal;

use app\util\Request as req;
use app\util\BaseURL as url;

use modele\DAO\journalDAO\BatDAO;
use vue\base\MainTemplate as Vue;
use app\util\Guard;

use modele\journal\Bat;
use modele\DAO\journalDAO\SpeciesDAO;

class SectionBat
{

    public function __construct()
    {
        $test = "oui";

        $urlAdd = url::getBaseUrl() . "sectionBat?page=addition";
        $urlModif = url::getBaseUrl() . "sectionBat?page=modification";
        $bat = null;

        // Page de la création fiche chauve-souris
        if (!req::has("page")) {

            // Tableau associatif, clé : idSpecies, valeur : commonName (de Species).
            $speciesDAO = new SpeciesDAO();
            $speciesAsso = [];
            $speciesList = $speciesDAO->getAllSpecies();
            foreach ($speciesList as $spe) {
                $speciesAsso[$spe->getId()] = $spe->getCommonName();
            }

            // Tableau correspondant au sexes possible
            $sexList = ["Inconnu", "Femelle", "Mâle"];

            $batDAO = new BatDAO();
            $batList = $batDAO->getAllBat();

            if (
                req::has("sectionTitle") &&
                req::has("sectionObservation")
            ) {
                //TODO Création et stockage de la fiche Bat.
            }

            Vue::render(
                'journal/SectionBat',
                [
                    "urlAdd" => $urlAdd,
                    "urlModif" => $urlModif,
                    "batList" => $batList,
                    "speciesList" => $speciesAsso,
                    "sexList" => $sexList
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
                        $sex = -1;
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
                $bat->addBat();
            }

            // Si c'est une modification de la chauve-souris
            $modif = req::has("id");
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
                    "bat" => $bat,
                    "modif" => $modif,
                    "allSpecies" => $allSpecies
                ]
            );
        }
    }
}