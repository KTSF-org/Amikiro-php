<?php

namespace controleur;

use app\util\Request as req;
use app\util\BaseURL as url;

use vue\base\MainTemplate as Vue;

use modele\Bat;
use modele\Section;

class SectionBat
{

    public function __construct()
    {
        $url = url::getBaseUrl() . "sectionBat?page=addition";

        if (!isset($_GET["page"])) {
            Vue::render(
                'SectionBat',
                ["url" => $url]
            );
            
        } else {
            // TODO 
            // Controle du formulaire d'ajout de Bat (si le nom existe déjà,
            // remplir tout le formulaire pour enregistrer dans la bdd ? ect)

            // Si le formulaire est remplis
            if (
                !empty($_POST["batName"]) &&
                !empty($_POST["batBirthDate"]) &&
                !empty($_POST["batSex"]) &&
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
                    $_POST["batBirthDate"],
                    $sex,
                    $_POST["batWeight"],
                    $_POST["batNotes"]
                );
                $bat->addBat();
            }

            Vue::render(
                'SectionBatAddition',
                ["url" => $url]
            );
        }
    }
}