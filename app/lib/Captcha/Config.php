<?PHP

namespace app\lib\Captcha;

/** magic config **/

class Config {
	
	public $_vars = array();
	protected static $_instance;

	public static function getInstance() {
		if (!isset(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	//& = reference 
	//https://www.php.net/manual/fr/language.references.return.php
	public function &__get($name) {
		return $this->_vars[$name];
	}

	public function __set ($name, $value) {
		$this->_vars[$name] = $value;
	}
	
}