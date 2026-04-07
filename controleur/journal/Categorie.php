<?php

namespace controleur\journal;

use vue\base\MainTemplate as Vue;
use app\util\Guard;

class Categorie {

    public function __construct() {
        Guard::requireRole(ROLE_NATURALISTE);
        Vue::setTitle('Édition Catégories');
        Vue::render('journal/Categorie', []);
    }
}
