<?php

namespace controleur;

use app\util\Request as req;
use vue\base\MainTemplate as Vue;

class Confidentialite {

    public function __construct() {
        Vue::render(
            'Confidentialite',
            []
        );
    }
}