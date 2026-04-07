<?php

namespace controleur;

use vue\base\MainTemplate as Vue;
use app\util\Guard;

class Journal {

    public function __construct() {
        Guard::requireRole(ROLE_ADHERENT);
        Vue::setTitle('Journal');
        Vue::render(
            'Journal',
            []
        );
        
    }
}