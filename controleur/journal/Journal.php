<?php

namespace controleur\journal;

use modele\DAO\journalDAO\SectionColonyDAO;
use modele\DAO\UserDAO;
use vue\base\MainTemplate as Vue;
use app\util\Guard;
use modele\DAO\journalDAO\SectionDAO;
use modele\DAO\journalDAO\SectionSpecimenDAO;
use app\util\BaseURL as url;
use app\util\SessionLogin;
use app\util\Request as req;

/**
 * Contrôleur : liste du journal d'observations.
 *
 * Affiche toutes les fiches de la table Section (ou seulement celles de
 * l'utilisateur connecté si le filtre "mesFiches" est actif).
 *
 * Pour chaque fiche, on détermine son type (Colonie ou Chauve-souris)
 * en cherchant un enregistrement correspondant dans ColonySection ou SpecimenSection.
 *
 * Suppression : accessible depuis cette page via un GET ?delete=true&id=X.
 * Seul le créateur de la fiche ou un admin peut la supprimer.
 */
class Journal
{

    public function __construct()
    {
        Guard::requireRole(ROLE_ADHERENT);

        $urlEditionBat     = url::getBaseUrl() . "sectionBat?edition=true";
        $urlEditionColonie = url::getBaseUrl() . "sectionColony?page=modification";
        $urlDelete         = url::getBaseUrl() . "/journal";
        $urlSectionRead    = url::getBaseUrl() . "/sectionRead";

        $sectionDAO       = new SectionDAO();
        $userDAO          = new UserDAO();
        $users            = $userDAO->findAll();
        $idUserSession    = SessionLogin::getUserId();
        $sectionColonyDAO = new SectionColonyDAO();
        $isAdmin          = SessionLogin::getRole() == ROLE_ADMIN;
        // Filtre "mes fiches" : activé par ?mesFiches=true dans l'URL
        $mesFiches = req::get("mesFiches") == "true";

        // Charge toutes les fiches ou seulement celles de l'utilisateur connecté
        if ($mesFiches) {
            $listFiches = $sectionDAO->findAllByAuth($idUserSession);
        } else {
            $listFiches = $sectionDAO->findAll();
        }

        // Tableau associatif [id_user => "Prénom Nom"] pour afficher l'auteur de chaque fiche
        $usersAsso = [];
        foreach ($users as $u) {
            $usersAsso[$u->getId()] = $u->getSurname() . " " . $u->getName();
        }

        // Détermine le type de chaque fiche ("Colonie" ou "Chauve souris") en consultant
        // la table de liaison correspondante. Le résultat est mis en cache dans $typeAsso
        // pour éviter N requêtes supplémentaires dans la vue.
        $type    = "";
        $typeAsso = [];
        foreach ($listFiches as $fiche) {
            // Une fiche présente dans ColonySection est une fiche colonie ;
            // sinon, elle est forcément liée à un individu (SpecimenSection).
            if ($sectionColonyDAO->findColonySectionByIdSection($fiche->getId())) {
                $type = "Colonie";
            } else {
                $type = "Chauve souris";
            }
            $typeAsso[$fiche->getId()] = $type;
        }

        // Suppression d'une fiche déclenchée par un lien GET ?delete=true&id=X.
        // Double vérification : seul le créateur ou un admin peut supprimer.
        // Après suppression, redirection pour éviter la re-soumission au rechargement.
        if (req::get("delete") == true) {
            $ficheId = req::get("id");
            $fiche   = $sectionDAO->find($ficheId);

            if ($fiche && $fiche->getIdUser() === $idUserSession || $isAdmin) {
                $fiche->deleteSection();
                header("Location: " . url::getBaseUrl() . "journal");
                exit();
            }
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
                'idUserSession' => $idUserSession,
                'urlDelete' => $urlDelete,
                'urlSectionRead'=> $urlSectionRead,
                'isAdmin' => $isAdmin,
                'mesFiches' => $mesFiches,
            ]
        );

    }
}
