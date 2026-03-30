<?php

/**
 *   AUTOLOADER
 *   La fonction spl_autoload_register va rechercher TOUS les namespaces déclarés,
 *   par exemple : $instance = new App\FirstClass;
 *   La variable $className sera le nom du namespace chargé, par exemple : App\FirstClass
 *   Sachant le nom du namespace, si l'on a choisi des répertoires du même nom, par exemple
 *   un répertoire App dans lequel un fichier FirstClass.php EXISTE alors on peut le charger
 *   avec REQUIRE
 */

class Autoloader {
    public static function run() {
        spl_autoload_register(function ($className) {

            //Chemin absolu du projet :
            $basePath = __DIR__ . DIRECTORY_SEPARATOR;

            //On redirige le chemin absolu a un niveau supérieur si le ficher index.php est absent :
            if (!file_exists($basePath . 'index.php')) $basePath = dirname($basePath) . DIRECTORY_SEPARATOR;

            //on remplace l'antislash par un slash apadaté à l'os (windows ou linux) :
            $classFile = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

            //Chemin absolu du fichier "Classe".php
            $classAbsPath = $basePath . $classFile;

            //Si ce fichier existe, on exclu le répertoire vendor de Composer,
            if (file_exists($classAbsPath) && strpos($classAbsPath, '/vendor/') === false) {
                //puis on charge le fichier :
                require $classAbsPath;
            }
        });
    }
}

Autoloader::run();
