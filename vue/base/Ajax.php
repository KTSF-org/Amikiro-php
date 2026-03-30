<?php

namespace vue\base;

abstract class Ajax {

	protected $method;
	protected string $message='GET param is empty.';

	public function __construct() {
		$this->processRequest($this->method);
	}

	private function processRequest($method) {

		if (!is_null($method) && method_exists($this, $method)) {
			$rawData = $this->$method();
		} else {
			$rawData = [ __CLASS__ => $this->message ]; //pour tester 
		}

		//Sortie sur le navigateur au format JSON :
		header('Content-Type: application/json');
		echo json_encode($rawData, JSON_PRETTY_PRINT);
		exit;
	}
}
