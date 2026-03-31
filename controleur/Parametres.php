<?php

namespace controleur;

use vue\base\MainTemplate as Vue;

class Parametres {

    public function __construct() {
        Vue::setTitle('Paramètres');
        Vue::render('Parametres', []);
    }
}
