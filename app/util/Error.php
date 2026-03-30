<?php

namespace app\util;

class Error {

	public static function checkModelArgs($arrVars, $cls, $tot): void {
		unset($arrVars['id'], $arrVars['param']); //$id et $param ne sont pas un argument du constructeur, on retire
		$r=null;
		$defArgs = count($arrVars); //alternative : self::countProperties($cls)
		$curArgs = count($tot);
		if($defArgs !== $curArgs){$r=' (<b>' . $curArgs . '</b> reçus)';}
		
		$i=-1;
		$keys = array_keys($arrVars);
		foreach ($keys as $key) {
			$i++;
			$newKey[] = '[' . (string)$i . '] ' . $key;
		}
		$keys = $newKey;
		unset($newKey);
		
		foreach($arrVars as $k => $var) {
			//echo $k . "-->" . $var . '<br>'; //il y a un décalage, normal ..
			if($var === null or $var === "") {
				throw new \InvalidArgumentException(
					"Argument(s) vide(s) ou absent(s) pour le constructeur : " . 
					"<b>" . $cls . "</b>" . 
					"<br/>Attention, il faut " . 
					"<b>" . $defArgs . "</b>" .
					' attributs <b>non vides</b> pour ce constructeur !' . $r . 
					'<br/>Paramètres attendus : <br /><b>' . implode('<br />', $keys) . '</b>' );
			}
		}
	}
	
	public static function setException(string $messageErreur): void {
		// L'argument 2 (limite) est optionnel mais plus performant.
		$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
		$func = $trace[1]['function'] . '()' ?? 'FonctionInconnue?'; //équivalent de __FUNCTION___
		// Ces deux paramètres ne permettent pas de cibler la fonction de la classe en question, juste l'instance :
		// $fichier_appelant = $trace[1]['file'] ?? 'Inconnu';
		// $ligne_appelante = $trace[1]['line'] ?? 'Inconnu';
		$cls = $trace[1]['class'] ?? 'ClasseInconnue?'; //équivalent de __CLASS__
		$msg = ' : ' . $messageErreur ?? null;
		echo '<pre>'.$cls . '->' . $func .  $msg;
		die;
	}

	//@non implémenté :
	private static function countProperties($cls): int {
		$reflection = new \ReflectionClass($cls);
		$properties = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE);
		return count($properties)-1; //$id n'est pas un argument du constructeur, on retire
	}
	
	//@non implémenté :
	public static function print(array $trace, int $wantedError=0): ?string {
		if (isset($trace[$wantedError])) {
			$firstTrace = $trace[$wantedError];
			$err = "<pre>";
			$err .= "Appelé dans : " . ($firstTrace['file'] ?? 'N/A') . " à la ligne " . ($firstTrace['line'] ?? 'N/A') . "\n";
			$err .= "Fonction : " . ($firstTrace['function'] ?? 'N/A') . "\n";
			$err .= "</pre>";
		}
		return $err;
	}

}
