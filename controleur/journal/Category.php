<?php

namespace controleur\journal;

use vue\base\MainTemplate as Vue;
use modele\DAO\journalDAO\CategoryDAO;
use app\util\Guard;

class Category {

public function __construct(){

    Guard::requireRole(ROLE_NATURALISTE);
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
