<?php

namespace vue\base;

class MainTemplate extends Template {
	
	/**
	 * @param $filename
	 * Nom du fichier sans .php contenu dans le répertoire : vue
	 * @param $variables
	 * paramètres passés dans un tableau []
	 * @param $fullPath
	 * Chemin absolu vers le fichier .php (la vue)
	 * @param $useTemplate
	 * N'inclu pas le header et le footer
	 * @return vue
	 */
	
    public static function render(string $filename, array $variables = [], string|bool $fullPath = false, bool $useTemplate = true): void {
		
		if($useTemplate) {
			static::$header = MAIN_TEMPLATE_DIR . "header.php";
			static::$footer = MAIN_TEMPLATE_DIR . "footer.php";
		}
		
		static::$filename=$filename;
		static::$variables=$variables;
		static::$fullPath=$fullPath;
		static::$useTemplate=$useTemplate;
		
		static::print();
    }
	
	/**
	 * Autres méthodes
	 */
	
	public static function setTitle(string $title): void {
		static::$title=$title;
	}
	
	public static function addJS(array $js): void {
		static::$customJS=$js;
	}
	
	public static function addCSS(array $css): void {
		static::$customCSS=$css;
	}
	
	public static function test(): void {
		static::$test=true;
	}
	
}