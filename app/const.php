<?php

/**
 *	CONSTANTES
 */

//Paramètres :
include('param.php');

//Globales :
define('SLASH', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__) . SLASH);
define('BASENAME', dirname($_SERVER['PHP_SELF']));

//Vues :
define('VUE_DIR', ROOT . 'vue' . SLASH);
define('AJAX_DIR', VUE_DIR . 'ajax' . SLASH);
define('MAIN_TEMPLATE_DIR', VUE_DIR . 'common' . SLASH);

//Application et fichiers internes :
define('APP_DIR', ROOT . 'app' . SLASH);
define('FILE_DIR', APP_DIR . 'file' . SLASH);

//Nom du répertoire des fichiers statique 
define('ASSET_DIR', ROOT . ASSET . SLASH);

//Config du SGBDR :
define('DB_CONFIG', include('DB.php'));
