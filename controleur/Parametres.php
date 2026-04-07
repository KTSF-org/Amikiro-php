<?php

namespace controleur;

use vue\base\MainTemplate as Vue;
use app\util\Guard;

class Parametres {

    public function __construct() {
        Guard::requireLogin();
        Vue::setTitle('Paramètres');
        Vue::render('Parametres', []);
    }
}
