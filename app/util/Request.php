<?php

namespace app\util;

/**
* 	Gestionnaire des requêtes de type $_POST et $_GET
*	Retourne des valeurs filtrées, non vulnérables côté sécurité
*/

class Request {
	
	// private static $get;
	// private static $post;
	// private static $initialized = false;
	
	/**
	* On prend les superglobales $_GET et $_POST comme valeurs de référence
	* pour les méthodes dans un lazzy loading
	*/
	// private static function init(): void {
		// if (!self::$initialized) {
			// self::$get = $_GET;
			// self::$post = $_POST;
			// self::$initialized = true;
		// }
	// }
	
	/**
	* Récupère une valeur de la requête GET et la nettoie.
	*
	* @param string $key La clé de la valeur à récupérer.
	* @param string $default La valeur par défaut si la clé n'existe pas.
	* @return string La valeur après un passage au niveau de la sécurité.
	*/
	public static function get(string $key, string $default=''): string {
		return empty($_GET[$key]) ? $default : self::sanitize($_GET[$key]);
	}

	/**
	* Récupère une valeur de la requête POST et la nettoie.
	*
	* @param string $key La clé de la valeur à récupérer.
	* @param string $default La valeur par défaut si la clé n'existe pas.
	* @return string La valeur après un passage au niveau de la sécurité.
	*/
	public static function post(string $key, string $default=''): string {
		return empty($_POST[$key]) ? $default : self::sanitize($_POST[$key]);
	}

	/**
	* Vérifie l'existence d'une clé dans la requête GET ou POST.
	*
	* @param string $key Clé de référence.
	* @return bool TRUE si la clé existe et seulement cette clé, FALSE sinon.
	*/
	public static function is(string $key): bool {
		return isset($_GET[$key]) || isset($_POST[$key]);
	}

	/**
	* Vérifie l'existence et la non-nullité d'une clé dans les données de la requête GET ou POST.
	*
	* @param string $key Clé de référence.
	* @return bool TRUE si la clé existe et a une valeur, FALSE sinon.
	*/
	public static function has(string $key): bool {
		return	( isset($_GET[$key]) && !empty($_GET[$key]) ) || 
				( isset($_POST[$key]) && !empty($_POST[$key] ) );
	}

	/**
	* Sécurise une string pour l'affichage ou le stockage.
	*
	* @param string $input La chaîne à nettoyer.
	* @param int $maxLength La longueur maximale autorisée (défaut: 256 caractères).
	* @param string $encoding L'encodage de la chaîne (défaut: UTF-8).
	* @return string La chaîne nettoyée.
	*/
	private static function sanitize(string $input, int $maxLength = 256, string $encoding = 'UTF-8'): ?string {
		if(!empty($input)) {
			
			$input = (string)$input;
			
			//On limitte la longueur de la chaine pour contrecarrer les débordements de tampon :
			$input = mb_substr($input, 0, $maxLength, $encoding);

			//On supprime les balises HTML afin d'éviter certaines attaques XSS :
			$input = strip_tags($input, '');

			//On supprime les espaces en début et fin de chaîne :
			$input = trim($input);

			//On échappe les caractères spéciaux pour l'affichage HTML (empêche d'exécuter du code) :
			$input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

			//On supprime les caractères de contrôle NUL, BS, HT, CR, DEL (sauf LF) :
			$input = preg_replace('/[\x00\x08\x0B\x0C\x0D]/u', '', $input);
		}

		return $input;
	}

	/**
	* Obtenir la taille d'une string varchar(num)
	* @prototype getEntityLength()
	*/
	// public static function getEntityLength() {
		// $sql = "DESCRIBE clients";
		// $stmt = $pdo->query($sql);
		// while ($row = $stmt->fetch()) {
			// if ($row['Field'] === 'nom') {
				// echo "La longueur maximale de la colonne 'nom' est : " . $row['Length'];
				// break;
			// }
		// }
	// }
	
	/**
	* Obtenir le type d'encodage (UTF-8, ou autres)
	* @prototype getEntityEncoding()
	*/
	// public static function getEntityEncoding() {
		// try {

		// $sql = "SELECT CHARACTER_SET_NAME
		// FROM INFORMATION_SCHEMA.COLUMNS
		// WHERE TABLE_SCHEMA = 'base_de_donnees'
		// AND TABLE_NAME = 'table'
		// AND COLUMN_NAME = 'nom'";

		// $stmt = $pdo->query($sql);
		// $result = $stmt->fetch();

		// if ($result) {
			// $encodage = $result['CHARACTER_SET_NAME'];
			// echo "L'encodage de la colonne 'nom' est : " . $encodage;
		// } else {
			// echo "Colonne non trouvée.";
		// }
		// } catch (PDOException $e) {
			// die("Erreur : " . $e->getMessage());
		// }
	// }
	
}
