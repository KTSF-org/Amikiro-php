<?php

namespace controleur\journal;

use modele\DAO\journalDAO\CategoryDAO;
use vue\base\MainTemplate as Vue;
use app\util\Guard;
use app\util\Request;
use app\util\BaseURL;
use modele\journal\Section;
use app\util\SessionLogin as UserSession;

class SectionColony {

    public function __construct() {
        Guard::requireLogin();
        Vue::setTitle('Section Colonies');

        $cat = new CategoryDAO();
        
        $allCategories = $cat->getAllCategories();
        $catList ="";
        foreach ($allCategories as $category) {
            $catList .= "<option value=" . $category->getId() . ">" . $category->getName() . "</option>";
        }
        
        
        $categories = $cat->getAllcategories();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = Request::post('colonyTitle');
            $content = Request::post('colonyContent');
            $creationDate = Request::post('colonyDate');
            $idUser = UserSession::getUserId();     

            if(empty($title) || empty($content) || empty($creationDate)) {
                $error = 'Tous les champs doivent être remplis.';
            } else {
                $section = new Section($title, $content, $creationDate, $idUser);
                if ($section->addSection()) {
                    header('Location: ' . BaseURL::getBaseUrl() . 'sectionColony');
                    exit;
                }
                $error = 'Erreur lors de la création de la section.';

        
        
            }
        }

        Vue::render(
            'journal/SectionColony',
            [
                'categories' => $catList
            ]
        );
    
    }

}