<?php

namespace vue\base;
use app\util\BaseURL as Util;

/**
 * BASE : VUE
 */

abstract class Template {
	
	protected static $filename;
	protected static $filepath;
	protected static $header;
	protected static $footer;
	protected static $variables;
	protected static $fullPath;
	protected static $useTemplate;
	protected static $title;
	protected static $customJS=[];
	protected static $customCSS=[];
	protected static $test=false;
	
	abstract public static function render(
		string $filename,
		array $variables = [],
		string|bool $fullPath = false,
		bool $useTemplate = true
	): void;
	
	protected static function print(): void {
		
		self::$filename .= '.php';
		self::$filepath = VUE_DIR . self::$filename;
		if(self::$fullPath) self::$filepath = self::$fullPath . self::$filename;
		
		if(self::$test) {
			$ut = ' utilisé : ';
			$vuePathStr = 'Chemin de la vue';
			$noTemplateStr = $vuePathStr . ' sans template : ';
			$vuePathStr .= $ut;
			debug(
				'Header' . $ut . self::$header,
				'Vue' . $ut .  self::$filename,
				self::$useTemplate ? $vuePathStr . VUE_DIR . self::$filename : $vuePathStr,
				'Footer' . $ut . self::$footer,
				self::$fullPath ? $noTemplateStr . self::$fullPath . self::$filename : $noTemplateStr,
			);
		}
		
		//Mise en tampon
		ob_start();
		
		$customCSS=null;
		if(!empty(self::$customCSS)) {
			foreach(self::$customCSS as $cssSrc) {
				$customCSS .= '<link rel="stylesheet" href="'.$cssSrc.'">'.PHP_EOL;
			}
		}
		
		$customJS=null;
		if(!empty(self::$customJS)) {
			foreach(self::$customJS as $jsSrc) {
				$customJS .= '<script src="'.$jsSrc.'"></script>'.PHP_EOL;
			}
		}
		
        // Déclare l'ensemble des variables présent dans la variable $variales pour
        // les rendre accessibles directement. Exemple :
        // array("nom" => "Jean", "tableau" => ['a','b','c']) va générer
        // $nom = "Jean" et $tableau = Array
        extract(self::$variables);
		
		//Pour les vues et ajax, le chemin actuel de l'application
		//A utiliser dans les balises <a href> ou le paramètre 'url' d'ajax.
		$actual_link = Util::getBaseUrl();
		
		//Balise <title> par défaut :
		$title = self::$title ?? APP_NAME;

		//Composition du DOM :
		if (self::$useTemplate) include(self::$header);
		include(self::$filepath);
		if (self::$useTemplate) include(self::$footer); 
		
		//Vide le tampon et l'envoi à la vue :
		print Util::rewriteBase(ob_get_clean());
	}
	
}
