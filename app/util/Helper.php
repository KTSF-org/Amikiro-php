<?php

namespace app\util;

/**
* Toutes méthodes utiles à l'application
*/

class Helper {

	/**
	* Génère une chaine de caractère aléatoire.
	* @param $int Longueur de la chaine
	* @return string
	*/
	public static function randNum(int $length=8): string {
		$rand = '';
		for($i = 0; $i < $length; $i++) {
			$rand .= mt_rand(0, 9);
		}

		return $rand;
	}
	
	/**
	* Transforme un boolean en chaine de caractère.
	* @param $bool true|false
	* @return string
	*/
	public static function convertBoolStr(bool $bool=false): string {
		return $bool ? 'true' : 'false' ;
	}

	/**
	* Génère un identifiant unique.
	* @param $strong Renforce la clé unique générée
	* @return string
	*/
	public static function makeId(bool $strong=false): string {
		static $s;
		
		if (!$s) {
			$s = true;
			mt_srand((int)round(((float)microtime(true) + 1) * time()));
		}
		
		$o = uniqid(random_int(0, mt_getrandmax()), true);
		
		if( $strong ) {
			$o = str_shuffle( bin2hex( random_bytes(6) . uniqid('', true) ) );
		} else {
			$o = str_shuffle(strtr($o, '.', '0'));
		}
		
		return $o;
	}
	
	/**
	* Supprime les accents et caractères spéciaux d’une chaîne.
	* @param $input La chaine à transformer
	* @return string
	*/
	public static function normelizeString(string $input=''): string {
		return \Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC')
		->transliterate($input);
	}
	
}
