<?php

namespace controleur;

use app\util\Request as req;
use vue\base\MainTemplate as Vue;

class Journal {

    public function __construct() {
        Vue::render(
            'SectionBat',
            []
        );
    }
}