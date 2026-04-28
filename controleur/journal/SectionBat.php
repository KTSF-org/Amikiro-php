<?php

namespace controleur\journal;

use app\util\Request as req;
use app\util\BaseURL as url;

use modele\DAO\journalDAO\BatDAO;
use vue\base\MainTemplate as Vue;
use app\util\Guard;
use app\util\Request;

use modele\journal\Bat;
use modele\Section;
use modele\DAO\journalDAO\SpeciesDAO;
use modele\Species;

class SectionBat
{

    public function __construct()
    {

        $url = url::getBaseUrl() . "sectionBat?page=addition";
        $bat = null;

        // Page de la fiche chauve-souris
        if (!isset($_GET["page"])) {

            // Tableau associatif, clé : idSpecies, valeur : commonName (de Species).
            $speciesDAO = new SpeciesDAO();
            $speciesAsso = [];
            $speciesList = $speciesDAO->getAllSpecies();
            foreach ($speciesList as $spe) {
                $speciesAsso[$spe->getId()] = $spe->getCommonName();
            }

            // Tableau
            $sexList = ["Inconnu", "Femelle", "Mâle"];
 
            $batDAO = new BatDAO();
            $batList = $batDAO->getAllBat();

            if (
                !empty($_POST["sectionTitle"]) &&
                !empty($_POST["sectionObservation"])
            ) {
                //TODO Création et stockage de la fiche Bat.
            }

            Vue::render(
                'journal/SectionBat',
                [
                    "url" => $url,
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
            $speciesList = "";
            foreach ($allSpecies as $species) {
                $speciesList .= "<option value='" . $species->getId() . "'>" .
                    $species->getCommonName() . "</option>";
            }

            // TODO 
            // Controle du formulaire d'ajout de Bat (si le nom existe déjà,
            // remplir tout le formulaire pour enregistrer dans la bdd ? ect)

            // Si le formulaire est remplis
            if (
                !empty($_POST["batName"]) &&
                !empty($_POST["batWeight"]) &&
                !empty($_POST["batNotes"])
            ) {
                // Création de la variable du sexe
                switch ($_POST["batSex"]) {
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
                    $_POST["batName"],
                    (int) $_POST["batSpecies"],
                    $_POST["batBirthDate"],
                    $sex,
                    $_POST["batWeight"],
                    $_POST["batNotes"]
                );
                $bat->addBat();
            }

            // Si c'est une modification de la chauve-souris
            $modif = Request::has("id");
            if ($modif) {
                $batId = Request::get("id", "");
                $batDAO = new BatDAO();
                $bat = $batDAO->getBatById((int) $batId);
                //TODO envoyer à la vue et tout
            }

            Vue::render(
                'journal/SectionBatAddition',
                [
                    "url" => $url,
                    "speciesList" => $speciesList,
                    "bat" => $bat
                ]
            );
        }
    }
}