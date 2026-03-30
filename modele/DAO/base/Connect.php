<?php

namespace modele\DAO\base;

use PDO;
use PDOException;

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
