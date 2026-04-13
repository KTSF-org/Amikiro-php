<?php

namespace controleur;

use app\util\Request as req;
use app\util\BaseURL as url;

use vue\base\MainTemplate as Vue;
use app\util\Guard;

use modele\Bat;
use modele\Section;
use modele\DAO\SpeciesDAO;
use modele\Species;

class SectionBat
{

    public function __construct()
    {

        $url = url::getBaseUrl() . "sectionBat?page=addition";

        // Page de la fiche chauve-souris
        if (!isset($_GET["page"])) {

            // Création du code html pour afficher la liste des chauve-souris de la BDD
            //TODO


            Vue::render(
                'SectionBat',
                ["url" => $url]
            );

        }
        // Page de l'ajout d'une chauve-souris
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

            Vue::render(
                'SectionBatAddition',
                [
                    "url" => $url,
                    "speciesList" => $speciesList
                ]
            );
        }
    }
}