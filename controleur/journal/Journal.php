<?php

namespace controleur\journal;

use modele\DAO\journalDAO\SectionColonyDAO;
use modele\DAO\UserDAO;
use vue\base\MainTemplate as Vue;
use app\util\Guard;
use modele\DAO\journalDAO\SectionDAO;
use modele\DAO\journalDAO\SectionSpecimenDAO;
use app\util\BaseURL as url;

class Journal
{

    public function __construct()
    {
        Guard::requireRole(ROLE_ADHERENT);

        $urlEditionBat = url::getBaseUrl() . "sectionBat?edition=true";
        $urlEditionColonie = url::getBaseUrl() . "ouioui";
        $sectionDAO = new SectionDAO();
        $listFiches = $sectionDAO->findAll();
        $userDAO = new UserDAO();
        $users = $userDAO->findAll();


        $sectionColonyDAO = new SectionColonyDAO();

        // Tableau associatif pour récuperér les noms et prénoms des users
        // [id_users => "Prénom Nom"]
        $usersAsso = [];
        foreach ($users as $u) {
            $usersAsso[$u->getId()] = $u->getSurname() . " " . $u->getName();
        }

        // DETERMINE LE TYPE DE FICHE
        // On parcours chaque fiche pour savoir si elle appartient à une colonie ou une chauve souris
        $type = "";
        $typeAsso = [];
        foreach ($listFiches as $fiche) {
            // Vérifie si l'ID de la section existe dans la table colonie
            if ($sectionColonyDAO->findColonySectionByIdSection($fiche->getId())) {
                $type = "Colonie";
            } else {
                // Si elle n'est pas dans la table colonie elle est forcément dans la spécimen
                $type = "Chauve souris";
            }
            // On stock le résultat dans un tableau [id_fiche => "Type"]
            $typeAsso[$fiche->getId()] = $type;
        }


        Vue::setTitle('Journal');
        Vue::render(
            'journal/Journal',
            [
                'listFiches' => $listFiches,
                'usersAsso' => $usersAsso,
                'typeAsso' => $typeAsso,
                'urlEditionBat' => $urlEditionBat,
                'urlEditionColonie' => $urlEditionColonie,
            ]
        );

    }
}
