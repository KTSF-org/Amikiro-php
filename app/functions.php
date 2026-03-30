<?php

/**
 *  **********************
 *  Globales APP Functions
 *  **********************
 */

/**
 * Débogage de l'application.
 * Ajoute une ou des variables, retourne un print_r() sur une vue et
 * quitte l'application.
 * Pour sortir un var_dump(), activer DEBUG_DUMP dans : app/param.php
 * @param mixed $args
 * @return die : string
 */

function debug() {
	$line = null;
	$params = func_get_args();
	$tot = func_num_args();
	debugErr($tot,__FUNCTION__);
	if($tot>1) {
		$line = PHP_EOL . str_repeat('-',100) . PHP_EOL . PHP_EOL;
	}
	echo "\n\n<pre>".$line;
	for ( $i = 0; $i < $tot; $i++ ) {
		if(DEBUG_DUMP) { var_dump($params[$i]); } else { print_r($params[$i]); }
		echo $line;
	}
	die('</pre>');
}

// Gestionnaire d'erreur
function debugErr($tot, $func) {
	if($tot===0)die("La fonction <b>" . $func . "()</b> n'a aucun paramètre !");
}

// Alias de debug(), dd() :
function dd() {
	debugErr(func_num_args(),__FUNCTION__);
	debug(...func_get_args());
}
