<?php

namespace controleur\admin;

use vue\base\MainTemplate as Vue;
use app\util\Guard;

class Utilisateurs {

    public function __construct() {
        Guard::requireRole(ROLE_ADMIN);
        Vue::setTitle('Gestion des utilisateurs');
        Vue::render('admin/Utilisateurs', []);
    }
}
