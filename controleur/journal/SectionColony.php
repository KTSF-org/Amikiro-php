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

class SectionColony {

    public function __construct() {

        $urlCreate = url::getBaseUrl() . "sectionColony?page=create";
        $urlModif = url::getBaseUrl() . "sectionColony?page=modification";
        $urlDelete = url::getBaseUrl() . "sectionColony?delete=true";
        $section = null;

        Guard::requireLogin();
        Vue::setTitle('Section Colonies');

        $cat = new CategoryDAO();

        $allCategories = $cat->getAllCategories();
        $catList ="";
        foreach ($allCategories as $category) {
            $catList .= "<option value=" . $category->getId() . ">" . $category->getName() . "</option>";
        }


        $sectionCol = new SectionColonyDAO();
        $modif = Request::get('page')=='modification';
        $sectionDAO = new SectionDAO();
        $sectionId = (int)Request::get('id');

        if($modif){

            $section = $sectionDAO->find($sectionId);
        }
        $sectionId = (int)Request::get('id');
        // var_dump($sectionId);
        // var_dump($modif);

        $catSection = $sectionCol->findColonySectionByIdSection($sectionId);

        if ($catSection === null) {
            $catSec = null;
            } else {
                $catSec = $cat->findById($catSection->getIdCategory());
            }



        // if($_SERVER['REQUEST_METHOD'] === 'POST') {
        //     $title = Request::post('colonyTitle');
        //     $content = Request::post('colonyContent');
        //     $creationDate = Request::post('colonyDate');
        //     $idUser = UserSession::getUserId();

        //     if(empty($title) || empty($content) || empty($creationDate)) {
        //         $error = 'Tous les champs doivent être remplis.';
        //     } else {
        //         $section = new Section($title, $content, $creationDate, $idUser);
        //         if ($section->addSection()) {
        //             header('Location: ' . BaseURL::getBaseUrl() . 'sectionColony');
        //             exit;
        //         }
        //         $error = 'Erreur lors de la création de la section.';



        //     }
        // }

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
