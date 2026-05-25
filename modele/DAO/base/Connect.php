<?php

namespace modele\DAO\base;

use PDO;
use PDOException;

/**
 * Utilitaire de connexion PDO.
 *
 * Crée et retourne une instance PDO à partir de la configuration définie dans app/DB.php
 * (chargée sous la constante DB_CONFIG dans app/Setup.php).
 *
 * Appelé une seule fois via Database::getPdo() (singleton).
 * Si la connexion échoue, affiche un message d'erreur lisible et retourne null.
 *
 * Options activées quand DB_DEBUG = true :
 *   - ERRMODE_EXCEPTION : toute erreur SQL lève une PDOException (au lieu de retourner false)
 *   - ATTR_EMULATE_PREPARES = false : force les vraies requêtes préparées (détecte les erreurs de type)
 */
class Connect {

	public static function run() {
		try {
			$config = DB_CONFIG;
			$pdo = new \PDO($config["DB_DSN"], $config["DB_USER"], $config["DB_PASSWORD"]);
			$pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
			
			/*** ACTIVER LE DEBUG DES REQUÊTES ***/
			if ($pdo && $config["DB_DEBUG"]) {
				// Le mode d'erreur : exception permet à PDO de nous prévenir fortement quand on fait une erreur de syntaxe
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				//PDO passe de toute façon des requêtes préparées et renvoie une erreur si elle n'est pas :
				$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			}

			return $pdo;
			
		} catch (PDOException $e) {
			$errConnect = "problème de connexion à la base de données : ";
			if ($e->getCode() == 1045) {
				$errorMessage = $errConnect . "<b>Nom d'utilisateur ou mot de passe incorrect.</b>";
			} else {
				$errorMessage = $errConnect . $e->getMessage();
				$errorMessage .= "<br/><b><span style='color:darkred'>Il faut renseigner le fichier : " . ROOT . "app" . SLASH . "DB.php</b></span>";
			}

			echo "<p>Une erreur s'est produite : " . $errorMessage . "</p>";

			return null;
		}
	}
}
