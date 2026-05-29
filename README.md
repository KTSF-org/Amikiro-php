# Amikiro — Application web de suivi faunistique

> Projet réalisé dans le cadre du **BTS SIO – option SLAM** (session 2026)  
> Commanditaire : association **KTSF** (Ktsf Tracking & Suivi Faunistique)

---

## Sommaire

1. [Contexte et problématique](#1-contexte-et-problématique)
2. [Fonctionnalités](#2-fonctionnalités)
3. [Guide de démonstration](#3-guide-de-démonstration)
4. [Gestion des droits](#4-gestion-des-droits)
5. [Technologies et choix techniques](#5-technologies-et-choix-techniques)
6. [Architecture MVC](#6-architecture-mvc)
7. [Modèle de données](#7-modèle-de-données)
8. [Maintenance](#8-maintenance)
9. [Évolutions envisageables](#9-évolutions-envisageables)
10. [Installation locale](#10-installation-locale)
11. [Structure des répertoires](#11-structure-des-répertoires)

---

## 1. Contexte et problématique

L'association **KTSF** assure le suivi scientifique de populations de chauves-souris. Avant ce projet, les observations étaient consignées sur papier, rendant difficiles leur partage, leur analyse et leur archivage sur le long terme.

**Problématique :** comment centraliser et sécuriser les données d'observation tout en permettant un accès différencié selon le rôle de chaque membre ?

**Réponse apportée :** une application web MVC développée en PHP 8.1, hébergeable sur tout serveur Apache/MariaDB, sans dépendance à des services tiers, permettant :
- la gestion des membres et de leurs droits d'accès temporisés,
- la tenue d'un journal d'observations naturalistes (individus ou colonies),
- la consultation d'un flux vidéo en direct depuis le terrain.

---

## 2. Fonctionnalités

### Gestion des utilisateurs
- Authentification avec CAPTCHA et régénération de l'ID de session (anti fixation)
- 4 niveaux de rôles hiérarchiques : Invité · Adhérent · Naturaliste · Administrateur
- Accès temporisé via abonnements (`startDate` / `endDate`) ; rétrogradation automatique à l'expiration
- Numéro adhérent au format `AMI-AAAA-NNNN`, généré automatiquement
- Administration complète des comptes (création, modification, activation/désactivation)

### Journal d'observations
- **Fiche individu** : observation liée à une chauve-souris identifiée (titre, date, notes, espèce, sexe, masse)
- **Fiche colonie** : observation collective catégorisée (titre, date, catégorie, notes)
- Gestion du référentiel espèces et des catégories d'observations
- Tableaux paginés et filtrables (DataTables) pour consultation et recherche

### Flux vidéo Live
- Lecture de flux `.m3u8` via HLS.js directement dans le navigateur
- Compteur de spectateurs en temps réel avec limite configurable
- Minuterie de session résistante aux rechargements de page (`live_started_at`)

### Administration
- Configuration du flux vidéo (URL, limite spectateurs, durée de session)
- Accès via une URL secrète distincte de la connexion membre
- Tableau de bord de gestion des utilisateurs avec export possible

---

## 3. Guide de démonstration

> **Ordre de passage suggéré pour l'oral — environ 20 à 25 minutes de démo**

### Étape 1 — Accueil et contexte (2 min)
- Ouvrir `http://localhost/Amikiro-php/` en tant que visiteur non connecté
- Montrer la page d'accueil : présentation de l'association, responsive mobile
- Montrer que le menu est limité (pas d'accès au journal)

### Étape 2 — Connexion et rôles (3 min)
- Se connecter avec un compte **Adhérent** → montrer les pages accessibles
- Se déconnecter, se connecter avec un compte **Naturaliste** → nouvelles options apparaissent (catégories, ajout d'individus)
- Se déconnecter, se connecter via l'URL admin → interface d'administration complète

### Étape 3 — Journal : fiche individu (5 min)
- Créer une nouvelle fiche individu : titre, date, observation
- Sélectionner une chauve-souris dans la liste (tableau avec radio-button)
- Ouvrir la modale de détail d'un individu (espèce, sexe, masse, date de naissance)
- Valider → vérifier que la fiche apparaît dans le journal

### Étape 4 — Journal : fiche colonie (3 min)
- Créer une fiche colonie : titre, date, catégorie (Tom Select avec recherche), notes
- Montrer le retour AJAX (message de succès sans rechargement de page)

### Étape 5 — Gestion des catégories (3 min)
- Ouvrir la page Catégories (rôle Naturaliste requis)
- Ajouter une catégorie via le formulaire
- Modifier une catégorie en inline (bouton crayon → champ inline → Valider)
- Supprimer une catégorie (confirmation JS)
- Montrer que le tableau DataTables se recharge automatiquement

### Étape 6 — Flux Live (3 min)
- Ouvrir la page Live
- Montrer le player HLS, le compteur de spectateurs, la minuterie
- Expliquer le comportement à la fermeture de l'onglet (`beforeunload`)

### Étape 7 — Administration (3 min)
- Accéder via l'URL admin
- Montrer la gestion des utilisateurs : tableau, modification de rôle, activation
- Montrer la configuration du flux vidéo

### Étape 8 — Responsive (1 min)
- Redimensionner le navigateur → hamburger navbar, colonnes qui s'empilent
- Montrer un formulaire sur mobile

---

## 4. Gestion des droits

> Ce tableau est la référence à avoir en tête pour répondre aux questions du jury.

| Page / Action | Invité (0) | Adhérent (1) | Naturaliste (2) | Admin (9) |
|---|:---:|:---:|:---:|:---:|
| Accueil | ✅ | ✅ | ✅ | ✅ |
| Page Live (visualisation) | ✅ | ✅ | ✅ | ✅ |
| Consulter le journal | ❌ | ✅ | ✅ | ✅ |
| Lire une fiche | ❌ | ✅ | ✅ | ✅ |
| Créer une fiche individu | ❌ | ✅ | ✅ | ✅ |
| Créer une fiche colonie | ❌ | ✅ | ✅ | ✅ |
| Ajouter / modifier un individu | ❌ | ❌ | ✅ | ✅ |
| Gérer les catégories | ❌ | ❌ | ✅ | ✅ |
| Gérer les utilisateurs | ❌ | ❌ | ❌ | ✅ |
| Configurer le flux vidéo | ❌ | ❌ | ❌ | ✅ |
| Profil personnel | ❌ | ✅ | ✅ | ✅ |

### Cas particuliers à connaître
- Un adhérent dont l'abonnement a expiré est **automatiquement rétrogradé à Invité (0) à sa prochaine connexion**
- La durée par défaut accordée aux invités est configurable via la table `Config` (`guestDefaultAccessDays`)
- L'accès administrateur passe par une **URL secrète distincte** (`app/const.php` → `URL_ADMIN`)
- `Guard::requireRole(ROLE_X)` est **toujours la première instruction** du constructeur de chaque contrôleur

---

## 5. Technologies et choix techniques

### Back-end

| Technologie | Version | Pourquoi ce choix |
|---|---|---|
| **PHP 8.1** | 8.1+ | Langage imposé par le contexte BTS SIO ; typage strict, fibers, enums disponibles |
| **Architecture MVC maison** | — | Permet de comprendre et de maîtriser chaque couche sans la "magie" d'un framework ; cohérent avec un contexte pédagogique |
| **Apache + mod_rewrite** | 2.4 | Standard des hébergements mutualisés français ; configuration via `.htaccess` sans accès root |
| **MariaDB / PDO** | 10.3+ | Requêtes préparées nativement → protection contre les injections SQL ; compatibilité MySQL totale |
| **Composer** | 2.x | Gestion propre des dépendances tierces sans copier-coller de code |
| **PHPMailer** | ^6.9 | Bibliothèque de référence pour l'envoi SMTP en PHP ; support TLS, pièces jointes |
| **SimpleImage** | ^4.0 | Traitement des avatars sans extension GD complexe |

### Front-end

| Technologie | Version | Pourquoi ce choix |
|---|---|---|
| **Bootstrap 5** | 5.3.3 | Framework CSS le plus documenté ; composants responsive prêts à l'emploi (navbar, modales, grid) |
| **jQuery** | 3.6.0 | Simplifie la manipulation DOM et les appels AJAX ; bien connu en contexte SLAM |
| **DataTables** | 1.13.6 | Pagination, tri et recherche côté client sans back-end supplémentaire |
| **Tom Select** | latest | Amélioration UX des `<select>` : recherche intégrée, création d'options à la volée |
| **HLS.js** | latest | Lecture de flux HLS (`.m3u8`) dans tous les navigateurs, y compris Chrome qui ne supporte pas HLS nativement |
| **Outfit (Google Fonts)** | v15 | Police sans-serif moderne, lisible, cohérente avec l'identité visuelle ; auto-hébergée en woff2 |
| **Bootstrap Icons** | 1.11.3 | Icônes vectorielles SVG via police CSS ; cohérence avec Bootstrap ; léger (130 Ko woff2) |

> **Choix structurant : 100 % auto-hébergé.** Toutes les bibliothèques sont dans `asset/lib/`. L'application fonctionne sans connexion internet, ce qui est un critère de fiabilité en production et en démo hors réseau.

---

## 6. Architecture MVC

```
Requête HTTP
    │
    ▼
index.php
    │
    ▼
app/route/Routing.php  ←─── app/route/routes.php
    │                             (déclaration des routes)
    ▼
controleur/XxxController.php
    │  __construct() : Guard → logique → Vue
    │
    ├── modele/DAO/XxxDAO.php     (accès données PDO)
    │       └── modele/Xxx.php   (objet métier)
    │
    └── vue/base/MainTemplate    (rendu HTML)
            ├── vue/common/header.php
            ├── vue/[page].php    (contenu)
            └── vue/common/footer.php
```

### Règles appliquées sans exception
- **Guard en première ligne** — `Guard::requireRole(...)` avant tout traitement dans chaque `__construct()`
- **Pas de `require` dans les vues** — l'autoloader PSR-0 maison gère tout
- **AJAX centralisé** — toutes les requêtes AJAX passent par `controleur/MainAjax.php`
- **Retours AJAX normalisés** — `"Success"` ou `"No success"` (sensible à la casse)
- **Sanitisation à l'entrée** — `htmlspecialchars` dans `Request::sanitize()` sur tout `$_GET`/`$_POST`

---

## 7. Modèle de données

```
User ──────────────── Abonnement
  │                      (startDate, endDate)
  │
  └── Section ─────────┬── SectionSpecimen ── Bat ── Species
       (titre, date,   │      (liaison 1:1)
        contenu, type) │
                       └── ColonySection ── Category
                              (liaison N:1)

Config  (ligne unique — URL flux, limite spectateurs, durées par défaut)
```

| Table | Description |
|---|---|
| `User` | Membres avec rôle (`0–9`), numéro adhérent `AMI-AAAA-NNNN`, avatar |
| `Abonnement` | Périodes d'accès par utilisateur (`startDate`, `endDate`) |
| `Config` | Configuration applicative (une seule ligne) |
| `Section` | Entrées du journal, type individu ou colonie |
| `Bat` | Individus suivis (espèce, sexe, masse, date de naissance, notes) |
| `Species` | Référentiel des espèces (nom commun, nom latin) |
| `SectionSpecimen` | Association section ↔ chauve-souris individuelle |
| `ColonySection` | Association section ↔ catégorie (colonie) |
| `Category` | Catégories d'observations de colonies |

---

## 8. Maintenance

> Cette section montre que l'on sait **intervenir sur le code** en situation réelle.

### 8.1 Lire une erreur PHP

Activer les erreurs pendant le développement dans `app/param.php` :
```php
define('DB_DEBUG', true);  // affiche les erreurs PDO
```
Et dans `index.php` ou `app/Setup.php` :
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```
En production : erreurs dans `/var/log/apache2/error.log` (Linux) ou `C:\xampp\apache\logs\error.log` (Windows).

### 8.2 Déboguer une variable PHP

La fonction `debug()` est disponible partout dans l'application :
```php
debug($section);           // affiche l'objet Section
debug($_SESSION, $_POST);  // plusieurs variables à la fois
dd($user);                 // alias, stoppe l'exécution après affichage
```
Activer `DEBUG_DUMP=true` dans `app/param.php` pour utiliser `var_dump` au lieu de `print_r`.

### 8.3 Déboguer une requête AJAX

Dans `app/param.php`, passer `AJAX_DEBUG` à `true` :
```php
define('AJAX_DEBUG', true);
```
Un popup apparaît dans le navigateur pour chaque requête AJAX : URL, méthode, données envoyées, réponse reçue.

### 8.4 Corriger une requête SQL

Les requêtes sont dans les DAO (`modele/DAO/`). Exemple de requête préparée à modifier :
```php
// modele/DAO/journalDAO/SectionDAO.php
$sql = "SELECT * FROM Section WHERE idUser = :id ORDER BY eventDate DESC";
$stmt = $this->pdo->prepare($sql);
$stmt->execute([':id' => $id]);
```
Toujours utiliser des **requêtes préparées** — ne jamais concaténer `$_POST` dans une requête.

### 8.5 Réinitialiser un mot de passe en base

Via phpMyAdmin ou en ligne de commande :
```sql
UPDATE User
SET password = '$2y$10$...'  -- hash BCrypt généré via password_hash()
WHERE email = 'utilisateur@example.com';
```
Pour générer un hash BCrypt en PHP :
```php
echo password_hash('nouveau_mot_de_passe', PASSWORD_BCRYPT);
```

### 8.6 Ajouter une route

Dans `app/route/Routing.php` :
```php
$route->add('/ma-page', 'controleur\MaPage');
$route->add('/ma-page', 'controleur\MaPage', 'param1');        // 1 paramètre
$route->add('/ma-page', 'controleur\MaPage', ['p1', 'p2']);    // plusieurs paramètres
```
Puis créer le fichier `controleur/MaPage.php` :
```php
namespace controleur;
use app\util\Guard;
class MaPage {
    public function __construct() {
        Guard::requireRole(ROLE_ADHERENT); // ← toujours en premier
        // logique métier ici
    }
}
```

### 8.7 Ajouter une colonne en base

1. Ajouter la colonne dans la table SQL
2. Ajouter le getter/setter dans l'objet métier (`modele/Xxx.php`)
3. Mettre à jour le DAO (`modele/DAO/XxxDAO.php`) pour lire/écrire la nouvelle colonne
4. Mettre à jour la vue concernée (`vue/xxx.php`)

### 8.8 Bugs connus résolus (exemples pour l'oral)

| Bug | Symptôme | Cause | Correction |
|---|---|---|---|
| DataTables v2 conflit | Tableau invisible | `Category.php` chargeait sa propre v2 en plus de la v1.13.6 du header | Suppression des balises CDN redondantes |
| Footer non collant | Le footer remontait au milieu de la page | `display:grid` sans `main` dédié | `body { display:flex }` + `<main class="page-body">` + `flex:1` |
| Modales dans `<tbody>` | DataTables comptait des lignes fantômes | `<div class="modal">` direct enfant de `<tbody>` est HTML invalide | Modales déplacées après `</table>` dans un second `foreach` |
| `beforeunload` au refresh | Minuterie Live se réinitialisait | `beforeunload` se déclenche aussi lors d'un rechargement | `live_started_at` protégé par `if (!isset(...))` |

---

## 9. Évolutions envisageables

> 5 pistes concrètes avec **comment les implémenter** — montre que l'on comprend l'architecture.

### 9.1 Export PDF des fiches d'observations

**Besoin :** permettre aux naturalistes de générer un rapport PDF d'une fiche ou d'une période.

**Comment faire :**
1. Ajouter la bibliothèque `dompdf/dompdf` via Composer
2. Créer `controleur/journal/SectionExport.php` avec `Guard::requireRole(ROLE_ADHERENT)`
3. Récupérer les données via les DAO existants, les passer à un template HTML dédié
4. Renvoyer le PDF en `Content-Type: application/pdf`

**Effort estimé :** 1 à 2 jours.

---

### 9.2 Cartographie des observations (carte interactive)

**Besoin :** afficher les lieux d'observation sur une carte pour visualiser les territoires couverts.

**Comment faire :**
1. Ajouter des colonnes `latitude` et `longitude` (DECIMAL) à la table `Section`
2. Intégrer la bibliothèque **Leaflet.js** (auto-hébergeable, licence libre)
3. Créer une vue carte qui charge les coordonnées via AJAX (`ajax?getSectionsMap`)
4. Afficher des marqueurs cliquables renvoyant vers les fiches

**Effort estimé :** 2 à 3 jours.

---

### 9.3 API REST pour une application mobile

**Besoin :** permettre à une application mobile (Android/iOS) de consulter le journal.

**Comment faire :**
1. Créer un nouveau groupe de routes `/api/v1/...` dans `Routing.php`
2. Créer des contrôleurs API (`controleur/api/`) qui retournent du JSON au lieu de HTML
3. Remplacer `Vue::render()` par `header('Content-Type: application/json'); echo json_encode($data);`
4. Ajouter un système de tokens (ex. JWT ou token en base) pour l'authentification mobile
5. Implémenter CORS si l'application mobile est hébergée sur un domaine différent

**Effort estimé :** 3 à 5 jours.

---

### 9.4 Notifications par e-mail

**Besoin :** alerter automatiquement un administrateur quand un abonnement expire ou quand une nouvelle fiche est créée.

**Comment faire :**
1. PHPMailer est **déjà installé** (`composer.json`) et configuré dans `app/util/Mailer.php`
2. Dans `controleur/common/Login.php` : après la rétrogradation d'un adhérent expiré, appeler `Mailer::send()` pour notifier l'admin
3. Dans `controleur/journal/SectionBat.php` / `SectionColony.php` : appeler `Mailer::send()` après création d'une fiche

**Effort estimé :** 0,5 jour (infrastructure déjà en place).

---

### 9.5 Statistiques et tableaux de bord

**Besoin :** donner aux responsables une vue synthétique : nombre d'observations par espèce, par mois, évolution des populations.

**Comment faire :**
1. Créer un contrôleur `controleur/journal/Stats.php` avec `Guard::requireRole(ROLE_NATURALISTE)`
2. Ajouter des requêtes d'agrégation SQL dans un nouveau `StatsDAO` (GROUP BY, COUNT, DATE_FORMAT)
3. Intégrer **Chart.js** (auto-hébergeable) pour les graphiques
4. Afficher camembert espèces, courbe temporelle, carte de chaleur mensuelle

**Effort estimé :** 2 à 3 jours.

---

## 10. Installation locale

### Prérequis
- PHP 8.1+, Apache avec `mod_rewrite`, MariaDB/MySQL, Composer

### Étapes

**1. Cloner le dépôt**
```bash
git clone <url-du-repo> Amikiro-php
cd Amikiro-php
composer install
```

**2. Créer les fichiers de configuration** *(exclus du dépôt — à créer manuellement)*

`app/DB.php` :
```php
<?php
return [
    'DB_DSN'      => 'mysql:host=localhost;dbname=KTSF',
    'DB_USER'     => 'root',
    'DB_PASSWORD' => '',
    'DB_DEBUG'    => true,
];
```

`app/param.php` :
```php
<?php
define('APP_NAME',       'Amikiro');
define('APP_VERSION',    '1.0');
define('MAIN_TITLE',     'Amikiro');
define('DEBUG_DUMP',     false);
define('AJAX_DEBUG',     false);
define('ASSET',          'asset');
define('ROLE_INVITE',      0);
define('ROLE_ADHERENT',    1);
define('ROLE_NATURALISTE', 2);
define('ROLE_ADMIN',       9);
```

**3. Importer la base de données**
```bash
mysql -u root -p -e "CREATE DATABASE KTSF CHARACTER SET utf8mb4;"
mysql -u root -p KTSF < KTSF.sql
```

**4. Configurer `.htaccess`**
```apache
# Sous-répertoire :
RewriteBase /Amikiro-php/
# Racine du domaine : laisser commenté
```

**5. Accéder à l'application**
```
http://localhost/Amikiro-php/
```

---

## 11. Structure des répertoires

```
Amikiro-php/
├── app/                        # Noyau du framework
│   ├── autoload.php            # Autoloader PSR-0 maison
│   ├── route/Routing.php       # Déclaration et dispatch des routes
│   ├── util/                   # Guard, Request, SessionLogin, BaseURL, Mailer
│   ├── DB.php                  # ⚠ Gitignored — connexion BDD
│   └── param.php               # ⚠ Gitignored — constantes applicatives
│
├── controleur/                 # Contrôleurs MVC
│   ├── MainAjax.php            # Point d'entrée unique pour tous les appels AJAX
│   ├── Live.php                # Flux vidéo en direct
│   ├── admin/                  # Gestion utilisateurs, configuration webcam
│   ├── common/                 # Accueil, Login, Logout, NotFound
│   ├── journal/                # Journal, fiches, catégories, espèces
│   └── util/                   # Captcha, Image, CustomJS
│
├── modele/                     # Couche métier et DAO
│   ├── DAO/base/Database.php   # Classe CRUD générique (PDO)
│   ├── DAO/                    # UserDAO, ConfigDAO, VideoDAO, SubscriptionDAO
│   └── DAO/journalDAO/         # BatDAO, SectionDAO, CategoryDAO, SpeciesDAO…
│
├── vue/                        # Vues (HTML + PHP)
│   ├── common/header.php       # Ouverture HTML, navbar, <main class="page-body">
│   ├── common/footer.php       # </main>, <footer>, scripts de fermeture
│   └── journal/                # Category, SectionBat, SectionColony, SectionRead…
│
├── asset/
│   ├── css/                    # main.css, accueil.css, live.css, login.css, admin.css
│   ├── js/                     # main.js, live.js, tableau.js, formulaire.js…
│   └── lib/                    # Bibliothèques front-end auto-hébergées
│       ├── bootstrap/          # Bootstrap 5.3.3
│       ├── bootstrap-icons/    # Bootstrap Icons 1.11.3 (CSS + woff2)
│       ├── jquery/             # jQuery 3.6.0
│       ├── datatables/         # DataTables 1.13.6 + traduction FR
│       ├── tom-select/         # Tom Select
│       ├── hlsjs/              # HLS.js
│       └── fonts/outfit/       # Police Outfit (woff2, auto-hébergée)
│
├── KTSF.sql                    # Schéma + données de démonstration
├── composer.json               # Dépendances PHP (PHPMailer, SimpleImage)
├── index.php                   # Point d'entrée unique
└── .htaccess                   # Réécriture d'URL Apache
```
