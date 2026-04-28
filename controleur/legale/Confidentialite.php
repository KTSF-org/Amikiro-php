<?php

namespace controleur\legale;

use app\util\Request as req;
use vue\base\MainTemplate as Vue;

class Confidentialite {

    public function __construct() {
        Vue::setTitle("Amikiro Live - Confidentialité");    
        Vue::render(
            'legale/Confidentialite',
            []
        );
    }
}