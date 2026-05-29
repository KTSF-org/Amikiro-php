<?php

namespace controleur\legale;

use vue\base\MainTemplate as Vue;
use app\util\Guard;

class Credits {
	public function __construct() {
		Guard::requireLogin();
		Vue::setTitle("Crédits");
		Vue::render('legale/Credits', []);
	}
}
