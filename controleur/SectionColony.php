<?php

namespace controleur;

use modele\DAO\CategoryDAO;
use vue\base\MainTemplate as Vue;
use app\util\Guard;

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
        Vue::render(
            'SectionColony',
            [
                'categories' => $catList
            ]
        );
    }
}

