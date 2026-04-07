<?php

namespace controleur\admin;

use vue\base\MainTemplate as Vue;
use app\util\Guard;

class Webcam {

    public function __construct() {
        Guard::requireRole(ROLE_ADMIN);
        Vue::setTitle('Configuration Webcam');
        Vue::render('admin/Webcam', []);
    }
}
