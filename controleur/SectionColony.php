<?php

namespace controleur;

use vue\base\MainTemplate as Vue;
use app\util\Guard;

class SectionColony {

    public function __construct() {
        Guard::requireLogin();
        Vue::render(
            'SectionColony',
            []
        );
    }
}