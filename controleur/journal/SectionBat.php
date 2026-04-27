<?php

namespace controleur\journal;

use app\util\Request as req;
use app\util\BaseURL as url;

use modele\DAO\journalDAO\BatDAO;
use vue\base\MainTemplate as Vue;
use app\util\Guard;

use modele\journal\Bat;
use modele\Section;
use modele\DAO\journalDAO\SpeciesDAO;
use modele\Species;

class SectionBat
{

    public function __construct()
    {

        $url = url::getBaseUrl() . "sectionBat?page=addition";

        // Page de la fiche chauve-souris
        if (!isset($_GET["page"])) {

            // Création du code html pour afficher la liste des chauve-souris de la BDD
            $speciesDAO = new SpeciesDAO();
            $batDAO = new BatDAO();
            $bats = $batDAO->getAllBat();
            $batList = "";
            $batDetailsModals = "";

            foreach ($bats as $bat) {

                $id = $bat->getId();
                $name = $bat->getName();
                $species = $speciesDAO->getSpeciesById($bat->getIdSpecies())->getCommonName();
                $sex = "";
                switch ($bat->getSex()) {
                    case 1:
                        $sex = "Femelle";
                        break;
                    case 2:
                        $sex = "Mâle";
                        break;
                    default:
                        $sex = "Inconnu";
                        break;
                }

                $details = '
                <button type="button" class="btn btn-sm"
                data-bs-toggle="modal" data-bs-target="#modal' . $id . '">
                    afficher
                </button>
                ';

                $batList .= "
                <div class='row'>
                    <div class='col-1 text-center border'>
                        <input type='radio' class='form-check-input' name='batSelected'>
                    </div>
                    <div class='col-2 text-center border'>" .
                    $id . "
                    </div>
                    <div class='col border'>" .
                    $name . "
                    </div>
                    <div class='col-2 border'>
                            " . $details . "
                        </div>
                </div>
                ";

                $batDetailsModals .= '
                <div class="modal fade" id="modal' . $id . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">' . $name . '</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Espece : ' . $species . '<br/>
                                Date de naissance : ' . $bat->getBirthDate() . '<br/>
                                Sexe : ' . $sex . '<br/>
                                Poids : ' . $bat->getWeight() . ' grammes<br/><br/>
                                Note :<br/> ' . $bat->getNote() . '
                            </div>
                        </div>
                    </div>
                </div>
                ';
            }

            if (
                !empty($_POST["sectionTitle"]) &&
                !empty($_POST["sectionObservation"])
            ) {
                //TODO Création et stockage de la fiche Bat.
            }


            Vue::render(
                'SectionBat',
                [
                    "url" => $url,
                    "batList" => $batList,
                    "batDetailsModals" => $batDetailsModals
                ]
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
                'journal/SectionBatAddition',
                [
                    "url" => $url,
                    "speciesList" => $speciesList
                ]
            );
        }
    }
}