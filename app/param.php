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

// Mode développement : court-circuite Guard (injecte une session ROLE_ADMIN fictive).
// Mettre à false avant tout déploiement.
const DEV_MODE = true;

//Nom du répertoire des fichiers statiques (images, js, css) :
const ASSET = 'asset';

// Rôles utilisateur
const ROLE_INVITE     = 0;
const ROLE_ADHERENT   = 1;
const ROLE_NATURALISTE = 2;
const ROLE_ADMIN      = 3;
