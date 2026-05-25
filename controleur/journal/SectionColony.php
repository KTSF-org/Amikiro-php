<?php

namespace controleur\journal;

use modele\DAO\journalDAO\CategoryDAO;
use modele\DAO\journalDAO\SectionColonyDAO;
use modele\DAO\journalDAO\SectionDAO;
use vue\base\MainTemplate as Vue;
use app\util\Guard;
use app\util\Request;
use app\util\BaseURL as url;
use app\util\BaseURL;
use modele\journal\Section;
use app\util\SessionLogin as UserSession;

/**
 * Contrôleur : formulaire de saisie d'une fiche d'observation colonie.
 *
 * Une fiche colonie est composée de deux enregistrements liés :
 *   - Section      : titre, contenu, date d'événement, auteur.
 *   - ColonySection : liaison entre la Section et la Category sélectionnée.
 *
 * Ce contrôleur gère trois cas via le paramètre GET ?page :
 *   ?page=create       → affiche le formulaire vide de création (géré via AJAX, MainAjax).
 *   ?page=modification → pré-charge la fiche ?id=X pour modification.
 *   ?delete=true&id=X  → supprime la fiche X (géré via AJAX, MainAjax).
 *
 * La persistance (création/mise à jour/suppression) est effectuée en AJAX
 * via MainAjax::addSectionCol() et MainAjax::updateSectionCol().
 * Ce contrôleur ne gère que la partie GET (affichage initial et pré-chargement).
 */
class SectionColony {

    public function __construct() {
        Guard::requireRole(ROLE_NATURALISTE);

        $urlCreate = url::getBaseUrl() . "sectionColony?page=create";
        $urlModif  = url::getBaseUrl() . "sectionColony?page=modification";
        $urlDelete = url::getBaseUrl() . "sectionColony?delete=true";
        $section   = null;

        Vue::setTitle('Section Colonies');

        $cat = new CategoryDAO();

        $allCategories = $cat->getAllCategories();
        // Construction d'une chaîne HTML <option> pour le <select> de la vue.
        // Alternative : passer $allCategories directement et boucler dans la vue.
        $catList = "";
        foreach ($allCategories as $category) {
            $catList .= "<option value=" . $category->getId() . ">" . $category->getName() . "</option>";
        }

        $sectionCol = new SectionColonyDAO();
        // $modif = true si on est en mode édition (?page=modification)
        $modif     = Request::get('page') == 'modification';
        $sectionDAO = new SectionDAO();
        $sectionId  = (int)Request::get('id');

        if ($modif) {
            // Charge la fiche existante pour pré-remplir le formulaire d'édition
            $section = $sectionDAO->find($sectionId);
        }
        $sectionId = (int)Request::get('id');

        // Recherche la liaison ColonySection pour l'id courant
        // (null si la fiche n'est pas encore liée à une catégorie, ex: nouvelle fiche)
        $catSection = $sectionCol->findColonySectionByIdSection($sectionId);

        if ($catSection === null) {
            $catSec = null;
        } else {
            // Charge l'objet Category pour afficher son nom dans la vue
            $catSec = $cat->findById($catSection->getIdCategory());
        }


        Vue::render(
            'journal/SectionColony',
            [
                'categories' => $catList,
                "url" => $urlCreate,
                "urlModif" => $urlModif,
                "urlDelete" => $urlDelete,
                "section" => $section,
                "modif"=>$modif,
                "catSection" => $catSection,
                "catSec" => $catSec,
                "sectionId" => $sectionId
            ]
        );



    }

}
