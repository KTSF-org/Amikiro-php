<?php

namespace controleur;

use app\util\Request as req;
use vue\base\MainTemplate as Vue;

class SectionColony {

    public function __construct() {
        Vue::render(
            'SectionColony',
            []
        );
    }
}