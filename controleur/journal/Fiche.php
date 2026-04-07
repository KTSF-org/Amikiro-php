<?php

namespace controleur\journal;

use vue\base\MainTemplate as Vue;
use app\util\Guard;

class Fiche {

    public function __construct() {
        Guard::requireRole(ROLE_NATURALISTE);
        Vue::setTitle('Édition Fiches Chauve-Souris');
        Vue::render('journal/Fiche', []);
    }
}
