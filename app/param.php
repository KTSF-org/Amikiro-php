<?php

/**
 *	PARAMETRES PRINCIPAUX DE L'APPLICATION
 */

//Nom de l'app par défaut :
const APP_NAME = 'AMIKIRO LIVE';

//A implémenter :
const APP_VERSION = '0.0.0';

//Titre principal de l'application :
const MAIN_TITLE = 'AMIKIRO LIVE';

//Fonction debug() : sort var_dump() à la place de print_r()
const DEBUG_DUMP = false;

//Affiche les informations de débogage sur les requêtes AJAX :
const AJAX_DEBUG = false; //true -> le navigateur à recharger

//Nom du répertoire des fichiers statiques (images, js, css) :
const ASSET = 'asset';

// Rôles utilisateur
const ROLE_INVITE     = 0;
const ROLE_ADHERENT   = 1;
const ROLE_NATURALISTE = 2;
const ROLE_ADMIN      = 3;

// PHPMailer — Dev : Mailpit localhost:1025 / Prod : remplacer par SMTP hébergeur
define('MAIL_HOST', getenv('MAIL_HOST') ?? 'mailpit');
define('MAIL_PORT', getenv('MAIL_PORT') ?? '1025');
define('MAIL_USER', getenv('MAIL_USER') ?? '');
define('MAIL_PASS', getenv('MAIL_PASS') ?? '');
define('MAIL_FROM', getenv('MAIL_FROM') ?? 'noreply@amikiro.fr');
define('MAIL_FROM_NAME', getenv('MAIL_FROM_NAME') ?? '');

