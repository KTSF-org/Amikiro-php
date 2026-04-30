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
const MAIL_HOST      = '';
const MAIL_PORT      = 465; // Port standard pour TLS
const MAIL_USER      = '';
const MAIL_PASS      = '';
const MAIL_FROM      = '';
const MAIL_FROM_NAME = 'Amikiro';

