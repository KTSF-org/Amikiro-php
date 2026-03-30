<?PHP

namespace controleur\util;

use app\lib\Captcha\Config;
use app\lib\Captcha\Render;

class Captcha {

	public function __construct() {

		$config = Config::getInstance();

		$config->font_file = ASSET_DIR . 'font/dax.ttf';
		// $config->border = false;
		// $config->font_size = 30;
		// $config->length = 6;
		// $config->badme = false;
		// $config->alphanum = true;

		$captcha = new Render();

		//enregistrement de la session sans espace, en minuscule, et au format md5  :
		$_SESSION["captchaCode"] = $captcha->session;

		//Rendu de l'image :
		$captcha->make();

		unset($config, $captcha);
	}
}
