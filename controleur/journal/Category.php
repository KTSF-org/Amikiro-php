<?php

namespace controleur\journal;

use vue\base\MainTemplate as Vue;
use modele\DAO\journalDAO\CategoryDAO;

class Category {

public function __construct(){

	$cat = new CategoryDAO();
	$allCategories = $cat->getAllCategories();

	



	Vue::setTitle('Journal');
        Vue::render(
            'journal/Category',
            [
                'categories' => $allCategories,
            ]
        );
	}
}
