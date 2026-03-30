<?php

namespace app\util;

class BaseURL {

	/**
	 *	Retourne l'URL de l'application
	 *	Ex: http://dom.test
	 */
	public static function getUrl(): string {
		return (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]/";
	}

	/**
	 *	Retourne l'URL de l'application et son répertoire d'installation
	 *	Ex: http://dom.test/sub/apptest
	 */
	public static function getBaseUrl(): string {
		$baseURL = self::getUrl();
		if (BASENAME !== '/') $baseURL .= trim(BASENAME,'/') . '/';
		return $baseURL;
	}

	/**
	 *	Remplace sur le DOM le nom du dossier statique (asset) 
	 *	par l'URL de l'application + le nom dossier statique
	 */
	public static function rewriteBase($dom): string {

		//rewriteBase() est chargée sur les vues, on en profite pour ajuster le .htaccess :
		self::rewriteHtaccess(ROOT . '.htaccess');

		$baseURL = self::getBaseUrl();

		if (!file_exists(ROOT . ASSET)) die("Le réperoire : <b>" . ROOT . ASSET . "</b> est absent ou mal configuré !");
		$regex = "/(<[^>]+)(src|href)=\"((?:\.\.\/|.\/)?(" . ASSET . "\/.*?))\"/i";

		// Fonction de rappel
		$callback = function ($matches) use ($baseURL) {
			return $matches[1] . $matches[2] . "=\"" . $baseURL . $matches[4] . "\"";
		};

		return preg_replace_callback($regex, $callback, $dom);
	}
	
	/**
	 *	Ajuste la cible l'erreur 404 en fonction de l'instruction RewriteBase
	 *	dans le fichier .htaccess. Permet de gérer correctement l'affichage des
	 *	des erreurs 404 sur les répertoires protégées. Point faible :
	 *	chargement du .htaccess à chaque requête !
	 */
	private static function rewriteHtaccess(string $file=''): void {
		if(file_exists($file)) {
			$data = file_get_contents($file);
			
			// Capture la valeur de RewriteBase (non commentée et unique) :
			$rwBase = "/^(?!#)\s*RewriteBase\s+(\S+)/m";
			$rwBaseStr = false;
			if (preg_match($rwBase, $data, $matches)) {
				$rwBaseStr = $matches[1];
				if(empty($rwBaseStr))$rwBaseStr=false;
			}
			
			// Trouve la première occurrence non commentée de ErrorDocument 404 :
			$err404 = '/^(?!#)\s*ErrorDocument 404 (\/.*?index)/m';
			$err404Str = false;
			if (preg_match($err404, $data, $matches)) {
				$err404Str = str_replace('index','',$matches[1]);
				if($err404Str==='/')$err404Str=false;
			}
			
			// Re-écriture du fichier .htaccess :
			if($rwBaseStr !== $err404Str) {
				// debug( "--- write --- \n" . $rwBaseStr . ' # ' . $err404Str );
				$ldata = explode(PHP_EOL, $data);
				$out=null;
				foreach($ldata as $l) {
					if (preg_match("/^(?!#)\s*ErrorDocument\s+404/i", $l)) {
						$v = basename($l);
						$str404 = 'ErrorDocument 404 ';
						if(empty($rwBaseStr))$rwBaseStr='/';
						$l = str_replace($str404,'', $l);
						$l = $str404 . $rwBaseStr . $v;
					}
					$out .= $l . PHP_EOL;
				}
				// debug($out);
				file_put_contents($file,$out);
			}
			unset($data);
		}
	}
	
	/**
	 *	Affiche et masque le $_GET['uri'] "404" par la route protégée reçue
	 */
	public static function convertUri(): string {	
		$uri = $_GET['uri'] ?? '';
		$realUri = $_SERVER['REQUEST_URI'];
		$protectedFolders = self::parseHtaccess( ROOT . '.htaccess' );
		// Si l'URI est "404" et que $realUri contient un slash :
		if ($uri === '404' && str_contains($realUri, '/')) {
			// On récupère tous les sous-dossiers de l'url :
			$targs = explode('/', $realUri);
			if (!empty($protectedFolders)) {
				// Si l'un des sous-dossiers est un répertoire protégé :
				foreach($targs as $targ) {
					if (in_array($targ, $protectedFolders)) {
						// on l'ajoute pour l'affichage du 404 :
						if (BASENAME === '/') {
							$uri = $realUri;
						} else {
							$uri = str_replace(BASENAME,'',$realUri);
						}
						$uri = ltrim($uri, '/');
						break;
					}
				}
			}
		}
		// debug($uri,$realUri,BASENAME,$targs??'',$protectedFolders);
		return $uri;
	}
	
	/**
	 *	Retrouve l'enssemble des répertoires protégés contenu dans le fichier .htaccess
	 */
	private static function parseHtaccess(string $file=''): array {
		$arr=[];
		// Capture tout ce qu'il y a entre les parenthèses sur la ligne :
		// RewriteRule ^(app|controleur|modele|vendor|vue) - [R=404,L]
		$regex = '/\(([^)]+)\)/'; 
		if(file_exists($file)) {
			$ht = file_get_contents($file);
			$lignes = explode(PHP_EOL, $ht);
			foreach ($lignes as $ligne) {
				if (strpos($ligne, 'RewriteRule ^(') !== false) { 
					if (preg_match($regex, $ligne, $matches)) {
						$arr = preg_split('/\|/', $matches[1]);
						break;
					}
				}
			}
		}
		return $arr;
	}
	
}
