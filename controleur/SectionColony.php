<?php

namespace controleur;

use vue\base\MainTemplate as Vue;
use app\util\Guard;

class SectionColony {

    public function __construct() {
        Guard::requireLogin();
        Vue::setTitle('Section Colonies');
        Vue::render(
            'SectionColony',
            []
        );
    }
}