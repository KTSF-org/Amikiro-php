
==================================
   Application MVC | PHP 8.1 et +
==================================

---------------
1/ Installation
---------------
Dézipper dans : 
-> www/apptest

L'ensemble des fichiers + répertoires est accessible dans : 
-> www/apptest

"apptest" étant la racine du projet, et donc accessible en localhost sur un navigateur :
-> http://localhost/apptest

----------------------------------
2/ Apache et la gestion du routing
----------------------------------
Pour faire fonctionner normalement le routage, modifier le fichier : 
-> .htaccess 

Dans ce fichier, décommenter la ligne : 
-> RewriteBase /apptest/
Le répertoire "c:\www\apptest" devient alors la racine du projet.

Si le nom du répertoire change, il faut alors également changer le nom de la racine. Ex:
-> Le répertoire "c:\www\projetweb"
-> RewriteBase /projetweb/

En PRODUCTION (sur le serveur sio56.org) cet exemple à ajouter/adapter :
-> RewriteBase /monprenon/E5/projetWeb/live/

ATTENTION : Respecter la casse sur les espaces des noms (NAMESAPCES)
En effet, en production le routage et l'autoloader peuvent cesser de fonctionner.
Par exemple un namespace nommée AjaxControl et sa classe MainAjax
ne doivent pas être appelé sous ce format : mainAjax ou Ajaxcontrol
AjaxControl ou MainAjax => OUI

------------------
3/ Base de données
------------------
Un exemple est inclus : 
-> apptest.sql 

Ce fichier à importer sur PhpMyAdmin dans une base de données nommée :
-> apptest

Le nom de cette BBD doit être changé sur le projet réel !

Pour configurer l'accès à cette BDD, le fichier : 
-> app/DB.php 
doit être renseigné.

Par défaut le nom d'utilisateur est "root", 
mais ceci peut changer en fonction du SGBDR !

----------------------------
4/ Le routage (redirections)
----------------------------
Fichier à analyser/modifier/éditer :
-> app/route/routing.php

### Exemple: $route->add('/cible', 'controleur\MaCible');
Le namespace doit être renseigné. Ici le namespace est : 
-> controleur\MaCible
Cette route donne sur un navigateur : 
-> http://localhost/apptest/cible
Ce contrôleur est chargé dans le répertoire local : 
-> "apptest/controleur/MaCible.php"

### Exemple avec une classe contenant un seul paramètre :
-> $route->add('/cible', 'controleur\MaCible', "param1");
-> Classe en question : public function __construct("param1"){...}

### Exemple avec une classe contenant plusieurs paramètres.
Se déclare avec un tableau :
-> $route->add('/cible', 'controleur\MaCible', ["param1", "param2"]);
-> Classe en question : public function __construct("param1", "param2"){...}

----------------
5/ Prise en main
----------------
Voir le fichier : 
-> controleur/Accueil.php

Et ses dépendances :
-> DAO : modele/DAO/UserDAO.php
--> Logique METIER : modele/User.php
-> VUE : vue/Accueil.php
--> AJAX VUE : vue/ajax/ajaxRechercher.php
--> AJAX CONTROL : controleur/MainAjax.php

Le nom des fichiers est arbitraire, mais leurs noms de classes 
doivent être adaptés en cas de changement, exemple :
-> Fichier : modele/Admin.php
-> Nom de la classe : Admin
-> Nom du namespace : modele
-> Accessibilité : new \modele\Admin();

Aucun require() ne doit être ajouté, l'ensemble est géré par le fichier : 
-> app/autoload.php

-----------
6/ Débogage
-----------
Accessible -partout- la fonction debug() permet de décomposer une variable,
et donc lire son contenu. Par exemple :
-> debug($_SESSION);
-> debug($maVar);
-> debug($_SESSION, $maVar, $_POST, $_GET);
Un alias de debug() est disponoble avec la méthode dd(), exemple : dd($_SESSION);
Source de la fonction debug() :
-> app/functions.php

Quelques classes implémentées utilisent aussi un débogage en interne, par exemple :
-> app/setup.php --> Router::help();
-> controleur/NotFound.php --> Vue::test();

Pour AJAX (sur le front) un débuggeur interne est visible par un pop-up sur le
navigateur. Pour l'activer :
-> app/param.php
-> Passer à "true" le paramètre "AJAX_DEBUG".
-> Recharger le navigateur, puis lancer une requête AJAX.

-----------
7/ Composer
-----------
Des librairies Composer peuvent être ajoutées dans le fichier :
-> composer.json

La librairie en question s'ajoute dans la partie :
-> "require": { "ma/librairie": "version" }

Ou plus simplement par la commande :
-> cd /chemin/absolu/vers/mon/app/ && composer require ma/librairie

Pour importer la librairie, et donc mettre à jour les librairies Composer :
-> cd /chemin/absolu/vers/mon/app/ && composer update

Composer est aussi disponible pour Windows :
-> https://getcomposer.org/Composer-Setup.exe

-----------
8/ Au final
-----------
Une bonne partie du code de cette application MVC est commenté !


