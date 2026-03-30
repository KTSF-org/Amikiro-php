<?php

namespace app\route\base;

/**
 * Simple routeur associé au fichier .htacess d'Apache
 * La route est interprétée par la valeur de $_GET['uri']
 */

class Router {

	private $_uri = [];
	private $_method = [];
	private $_uvar = [];
	private $custom = null;

	/**
	 * @param string $uri Ajoute l'identifiant GET de l'url [ex: localhost/ma_route]
	 * @param string $method Ajoute la méthode choisies par un namespace du contrôleur
	 * @param null|string|array $param Ajoute un ou plusieurs paramètres sur le constructeur du contrôleur
	 */
	public function add($uri, $method, null|string|array $param = null): void {

		if (!is_string($uri)) {
			throw new \Exception('Le paramètre $uri doit être une chaîne de caractères, exemple : "/ma_route" ');
		}

		if (!(is_string($method) || $method instanceof \Closure)) {
			throw new \Exception('Le paramètre $method doit être le namespace d\'un contrôleur/méthode, exemple : "controleur\MaClasse" ');
		}

		if ($uri === '/404' or $uri === '404') {
			throw new \Exception("routing.php : [404] : Cette route '404' est protégée et ne peut être utilisée ! Utilisez la méthode : set404(namespace_du_controleur) pour la gestion des erreurs 404 ");
		}

		$this->_uri[] = '/' . trim($uri, '/');
		$this->_method[] = $method ?? ''; // Valeur par défaut si $method est null
		$this->_uvar[] = $param ?? ''; // Valeur par défaut si $param est null
	}



	/**
	 * Retourne la liste construite du routing 
	 */
	public function help(): string {

		function colorize(string $value, string|bool $color = false): string {
			return '<mark' . ($color ? ' style="background:' . $color . '"' : '') . '>' . $value . '</mark>';
		}

		foreach ($this->_uri as $key => $value) {
			$this_param = null;
			if (!empty($this->_uvar[$key])) {
				$this_param = ', ' . colorize( json_encode($this->_uvar[$key]) );
			}
			$this_method = $this->_method[$key];
			$this_name = is_string($this_method) ? colorize($this_method, '#D5F0FF') : $value;
			$route_name = trim(dirname($_SERVER['SCRIPT_NAME']) . $value, '/');
			if ($route_name === trim($this_name, '/')) $this_name = colorize('call_user_func()', '#FFAAAA');
			$arr[$route_name] = $this_name . $this_param;
		}
		
		echo '<pre>###### <b>Index du routing</b> ######' . PHP_EOL;
		echo 'Paramétré dans : ' . colorize($this->getRoutesInfos(), '#DFFFCC') . PHP_EOL;
		echo 'Tableau associatif retourné :' . PHP_EOL;
		print_r($arr);
		die('</pre>');
	}
	
	private function getRoutesInfos(): string|null {
		$cls = new \app\route\Routing();
		$reflectionClass = new \ReflectionClass($cls);
		if($reflectionClass)unset($cls);
		return $reflectionClass ? $reflectionClass->getFileName() : null;
	}

	/**
	 * Ajoute une route 404 par défaut
	 * @param $func Namespace du contôleur 404
	 */
	public function set404($func): void {
		$this->custom = $func;
	}

	/**
	 * Réception des requêtes $_GET['uri'] filtrée et routage de la valeur
	 * Construis la sous-classe sélectionnée et implémente ses paramètres
	 * Accepte les fonctions personnelles au niveau de la racine
	 */
	public function run(): void {

		$route = false;
		$sanitizeUri = filter_input(INPUT_GET, 'uri', FILTER_SANITIZE_SPECIAL_CHARS);
		$uriGetParam = isset($_GET['uri']) ? '/' . $sanitizeUri : '/';

		foreach ($this->_uri as $key => $value) {
			if ('/' . reset($_GET) === $value) $route = true;
			if (preg_match("#^$value$#", $uriGetParam)) {
				is_array($this->_uvar[$key]) ? $paramIsArray = true : $paramIsArray = false;
				$this->setup($this->_method[$key], $this->_uvar[$key], $paramIsArray);
			}
		}

		if (!$route) {
			if (empty($this->custom)) {
				$this->custom = function () {
					echo '404 : page not found !';
				};
			}
			$this->setup($this->custom);
		}
	}

	private function setup($this_method, $this_uvar = [], $paramIsArray = false): void {
		if (is_string($this_method)) {
			if ($paramIsArray) {
				//plusieurs paramètres à appliquer sur la classe :
				new $this_method(...$this_uvar);
			} else {
				//un seul (ou rien) :
				new $this_method($this_uvar);
			}
		} else {
			call_user_func($this_method);
		}
	}
}
