<?php

namespace controleur\admin;

use app\util\Helper as Util;
use app\util\SessionLogin as User;

class Test {

	public function __construct($param1, $param2) {
		
		/**
		*	ICI l'affichage est à titre d'exemple
		*	Une VUE doit être implémentée !
		*/
		
		echo "C'est un test dans un sous-répertoire du contrôleur.<br />";
		echo "Les paramètres reçus par le routing sont : " . $param1 . ' ' . $param2 . '<br />';
		echo "Identifiant à clé unique générée : " . Util::makeId(true) . '<br />';
		
		if (User::isLogin()) {
			$activeUser=null;
			if(isset($_SESSION['user'])) {
				$user = $_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom'];
				$activeUser = "<b>" . $user . "</b> est connecté.";
			}
			echo "La session LOGIN est activée ! " . $activeUser;
		} else {
			echo "<b>Recharger cette page ...</b>";
		}
		
		if( User::isLogin() ) {
			DEBUG_DUMP ? $v = 'var_dump' : $v = 'print_r';
			echo "<br/><pre>Sessions active de l'application -> $v(".chr(36)."_SESSION) :</pre>";
			debug($_SESSION);
		}
		
		User::login();
		
	}
}
