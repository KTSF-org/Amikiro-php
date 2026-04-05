<?php

namespace controleur;

use vue\base\MainTemplate as Vue;
use app\util\Guard;

class SectionBat {

    public function __construct() {
        Guard::requireLogin();
        Vue::render(
            'SectionBat',
            []
        );
    }
}