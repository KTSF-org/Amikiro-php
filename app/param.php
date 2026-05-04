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
const AJAX_DEBUG = true; //true -> le navigateur à recharger

//Nom du répertoire des fichiers statiques (images, js, css) :
const ASSET = 'asset';

// Rôles utilisateur
const ROLE_INVITE     = 0;
const ROLE_ADHERENT   = 1;
const ROLE_NATURALISTE = 2;
const ROLE_ADMIN      = 3;

// PHPMailer — Postfix sur l'hôte (host.docker.internal:25, pas d'auth)
const MAIL_HOST      = 'postfix';
const MAIL_PORT      = 25;
const MAIL_USER      = '';
const MAIL_PASS      = '';
const MAIL_FROM      = 'noreply@amikiro.fr';
const MAIL_FROM_NAME = 'Amikiro';

