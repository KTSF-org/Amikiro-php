# Amikiro — Guide Administrateur

> Application web de suivi faunistique — Association **KTSF**  
> Projet **BTS SIO SLAM** — Session 2026

---

## Sommaire

1. [Présentation](#1-présentation)
2. [Prérequis techniques](#2-prérequis-techniques)
3. [Installation](#3-installation)
4. [Configuration de l'application](#4-configuration-de-lapplication)
5. [Base de données](#5-base-de-données)
6. [Accès administrateur](#6-accès-administrateur)
7. [Gestion des utilisateurs](#7-gestion-des-utilisateurs)
8. [Configuration Webcam](#8-configuration-webcam)
9. [Configuration mail SMTP](#9-configuration-mail-smtp)
10. [Gestion des droits](#10-gestion-des-droits)
11. [Maintenance](#11-maintenance)
12. [Structure des répertoires](#12-structure-des-répertoires)

---

## 1. Présentation

Amikiro est une application web MVC développée en PHP 8.1 pour l'association KTSF. Elle permet :

- La gestion des membres et de leurs droits d'accès temporisés
- La tenue d'un journal d'observations naturalistes (individus ou colonies de chauves-souris)
- La consultation d'un flux vidéo en direct depuis le terrain
- L'envoi automatique d'emails de bienvenue aux nouveaux membres (via SMTP configurable)

L'application fonctionne entièrement **hors ligne** — toutes les bibliothèques front-end sont auto-hébergées dans `asset/lib/`.

---

## 2. Prérequis techniques

| Composant | Version minimale |
|-----------|-----------------|
| PHP | 8.1+ |
| Apache | 2.4+ avec `mod_rewrite` activé |
| MariaDB / MySQL | 10.3+ |
| Composer | 2.x |

> **Hébergement mutualisé :** vérifier que `mod_rewrite` est activé et que PHP 8.1 est disponible.

---

## 3. Installation

### 3.1 Déployer les fichiers

Transférer l'ensemble du dépôt sur le serveur (FTP, Git, ZIP) :

```bash
git clone <url-du-repo> Amikiro-php
cd Amikiro-php
composer install
```

Vérifier que le serveur web peut lire tous les fichiers. Aucun dossier d'upload n'existe à ce stade — aucune permission d'écriture spéciale n'est requise par défaut.

### 3.2 Configurer `.htaccess`

Ouvrir `.htaccess` à la racine et décommenter `RewriteBase` selon l'emplacement :

```apache
# Si l'app est dans un sous-répertoire (ex. /Amikiro-php/) :
RewriteBase /Amikiro-php/

# Si l'app est à la racine du domaine : laisser cette ligne commentée
```

### 3.3 Installer les dépendances PHP

```bash
composer install
```

Cela installe `phpmailer/phpmailer` et `claviska/simpleimage` dans `vendor/`.

---

## 4. Configuration de l'application

Deux fichiers sont **exclus du dépôt** (`.gitignore`) et doivent être créés manuellement **avant le premier lancement**.

### 4.1 `app/DB.php` — Connexion base de données

```php
<?php
return [
    'DB_DSN'      => 'mysql:host=localhost;dbname=KTSF',
    'DB_USER'     => 'root',
    'DB_PASSWORD' => '',
    'DB_DEBUG'    => false, // true en développement uniquement
];
```

### 4.2 `app/param.php` — Constantes applicatives

```php
<?php
define('APP_NAME',        'Amikiro');
define('APP_VERSION',     '1.0');
define('MAIN_TITLE',      'Amikiro');
define('DEBUG_DUMP',      false);  // true = var_dump au lieu de print_r
define('AJAX_DEBUG',      false);  // true = popup navigateur sur chaque appel AJAX
define('ASSET',           'asset');

// Rôles utilisateur (hiérarchie entière — ne pas modifier)
define('ROLE_INVITE',      0);
define('ROLE_ADHERENT',    1);
define('ROLE_NATURALISTE', 2);
define('ROLE_ADMIN',       3);
```

> ⚠ **Ne jamais passer `DEBUG_DUMP` ou `AJAX_DEBUG` à `true` en production.**

---

## 5. Base de données

### 5.1 Créer la base et importer le schéma

```bash
mysql -u root -p -e "CREATE DATABASE KTSF CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p KTSF < KTSF.sql
```

Le script `KTSF.sql` crée toutes les tables, injecte les catégories par défaut et le compte administrateur initial.

### 5.2 Appliquer la migration mail si le dump sql n'est pas à jour

Après l'import initial, exécuter le script de migration pour ajouter les colonnes de configuration SMTP à la table `Config` :

```bash
mysql -u root -p KTSF < migration_mail_config.sql
```

> Ce script est à exécuter **une seule fois**. Il ajoute les colonnes `mailHost`, `mailPort`, `mailUser`, `mailPass`, `mailFrom` et `mailFromName` à la table `Config`.

### 5.3 Compte administrateur initial

Le script SQL injecte un compte admin avec les identifiants suivants (à **changer immédiatement** après le premier accès) :

| Champ | Valeur par défaut |
|-------|-------------------|
| Email | `admin@amikiro.fr` |
| Mot de passe | `l2se4qyL206_` |

---

## 6. Accès administrateur

L'administrateur dispose de **deux portails de connexion distincts** :

| Portail | URL | Usage |
|---------|-----|-------|
| Membres | `https://votre-domaine.fr/login` | Connexion standard pour tous les profils |
| Admin | `https://votre-domaine.fr/df6hj98d24vp` | Accès exclusif à l'interface d'administration |

> ⚠ **L'URL admin (`df6hj98d24vp`) est définie dans `app/const.php`**. Ce fichier est versionné — pour plus de sécurité, déplacer cette constante dans `app/param.php` (gitignored) avant la mise en production.

Un compte admin **ne peut pas** se connecter via le portail membres (`/login`), et inversement. Les deux portails sont complètement séparés.

Une fois connecté, le menu **Paramètres** donne accès à trois sections d'administration :

- **Gestion des utilisateurs** → `/parametres/utilisateurs`
- **Configuration Webcam** → `/parametres/webcam`
- **Configuration mail** → `/parametres/mail`

Ces trois pages sont reliées entre elles par une barre de navigation rapide en haut de chaque page.

---

## 7. Gestion des utilisateurs

Accessible via **Paramètres → Gestion des utilisateurs**.

### 7.1 Créer un compte

Cliquer sur **+ Créer un compte** en haut à droite. Renseigner :

- Prénom et nom
- Adresse email (identifiant de connexion)
- Rôle initial : Invité, Adhérent, Naturaliste ou Admin
- Durée d'accès en jours (pour les profils Adhérent et Invité)

Un **mot de passe provisoire** est généré automatiquement et envoyé par email au nouveau membre (nécessite que la configuration SMTP soit renseignée — voir [section 9](#9-configuration-mail-smtp)).

Un **numéro adhérent** au format `AMI-AAAA-NNNN` est attribué automatiquement aux profils Adhérent et Naturaliste.

### 7.2 Modifier un compte

Cliquer sur l'icône crayon sur la ligne de l'utilisateur. Il est possible de modifier :

- L'identité (prénom, nom)
- Le rôle
- La durée d'accès (extension ou réduction de l'abonnement)
- Le mot de passe (un nouveau mot de passe provisoire peut être envoyé par email)

### 7.3 Supprimer et purger des comptes

- **Suppression individuelle** : icône corbeille sur la ligne → confirmation → suppression définitive.
- **Purge des invités expirés** : le bouton **Purger les invités expirés** (visible uniquement s'il y en a) supprime en une seule action tous les comptes invités dont la durée d'accès est écoulée.

### 7.4 Paramètres d'accès par défaut

Le formulaire **Paramètres d'accès** en haut de la page permet de configurer :

| Paramètre | Description |
|-----------|-------------|
| **Accès invité par défaut** | Durée (en jours) accordée automatiquement à un Adhérent rétrogradé Invité après expiration de son abonnement |
| **Accès naturaliste par défaut** | Durée d'accès initialement attribuée lors de la création d'un compte Naturaliste |

### 7.5 Recherche et filtres

- **Barre de recherche** : filtre les utilisateurs par nom ou prénom via AJAX (sans rechargement de page).
- **Onglets de rôle** : Tous / Invités / Adhérents / Naturalistes filtrent l'affichage du tableau.

---

## 8. Configuration Webcam

Accessible via **Paramètres → Configuration Webcam**.

### 8.1 URL du flux vidéo

Renseigner l'URL du flux caméra. Formats supportés :

| Format | Exemple |
|--------|---------|
| HLS | `http://192.168.1.10:8080/stream.m3u8` |
| HTTP direct | `http://192.168.1.10/mjpeg` |

> La lecture HLS est assurée par **HLS.js** côté navigateur. Un flux RTSP doit être converti en HLS en amont (ex. via FFmpeg).

### 8.2 Viewers simultanés

Nombre maximum de spectateurs pouvant visionner le flux en même temps.  
Mettre `0` pour un accès illimité. Recommandé pour les premiers tests : **10**.

### 8.3 Durée de session

Durée maximale (en secondes) d'une session de visionnage avant déconnexion automatique du flux.  
Mettre `0` pour une durée illimitée. Exemple : `3600` = 1 heure.

---

## 9. Configuration mail SMTP

Accessible via **Paramètres → Configuration mail**.

La configuration SMTP est stockée en base de données et modifiable à tout moment sans toucher au code. Elle est utilisée pour l'envoi automatique des **emails de bienvenue** lors de la création d'un compte utilisateur.

### 9.1 Paramètres disponibles

| Champ | Description | Exemple |
|-------|-------------|---------|
| **Hôte SMTP** | Serveur de relais SMTP | `smtp-relay.brevo.com` |
| **Port** | Port SMTP | `587` |
| **Identifiant SMTP** | Login SMTP ou clé API | `user@example.com` |
| **Mot de passe / Clé API** | Laisser vide pour conserver le mot de passe enregistré | — |
| **Adresse expéditeur** | Adresse affichée dans le champ « De : » | `noreply@amikiro.fr` |
| **Nom affiché** | Nom affiché à côté de l'adresse expéditeur | `Amikiro` |

### 9.2 Choix du port

| Port | Chiffrement | Quand l'utiliser |
|------|-------------|-----------------|
| `587` | STARTTLS *(recommandé)* | Brevo, SendGrid, Gmail, OVH |
| `465` | SMTPS (SSL direct) | Certains hébergements anciens |
| `25` | Aucun | Serveur local Postfix sans auth |
| `2525` | STARTTLS | Alternative si 587 est bloqué |

### 9.3 Configuration avec Brevo (recommandé pour la production)

[Brevo](https://www.brevo.com) est le relais SMTP recommandé. Gratuit jusqu'à 300 emails/jour.

1. Créer un compte sur [brevo.com](https://www.brevo.com)
2. Aller dans **Paramètres → SMTP & API → Clés SMTP**
3. Générer une clé SMTP
4. Renseigner dans Amikiro :

| Champ | Valeur |
|-------|--------|
| Hôte SMTP | `smtp-relay.brevo.com` |
| Port | `587` |
| Identifiant SMTP | Votre email de compte Brevo |
| Mot de passe / Clé API | La clé SMTP générée |
| Adresse expéditeur | Une adresse vérifiée dans Brevo |

> ⚠ Le mot de passe à renseigner est la **clé SMTP Brevo**, pas le mot de passe du compte Brevo. Les deux sont différents.

### 9.4 Configuration locale avec Mailpit (développement)

[Mailpit](https://github.com/axllent/mailpit) intercepte les emails localement sans les envoyer réellement. Idéal pour les tests.

| Champ | Valeur |
|-------|--------|
| Hôte SMTP | `localhost` (ou `host.docker.internal` sous Docker) |
| Port | `1025` |
| Identifiant SMTP | *(laisser vide — l'authentification est désactivée automatiquement)* |
| Mot de passe | *(laisser vide)* |
| Adresse expéditeur | N'importe quelle adresse |

Interface web de Mailpit : `http://localhost:8025`

### 9.5 Tester la configuration

Après avoir sauvegardé, créer un compte utilisateur via **Gestion des utilisateurs → + Créer un compte** : un email de bienvenue doit arriver dans la boîte du destinataire (ou dans Mailpit si en local).

En cas d'erreur d'envoi, consulter les logs PHP :
- Linux : `/var/log/apache2/error.log`
- Windows (XAMPP) : `C:\xampp\apache\logs\error.log`

---

## 10. Gestion des droits

| Page / Action | Invité (0) | Adhérent (1) | Naturaliste (2) | Admin (3) |
|---|:---:|:---:|:---:|:---:|
| Accueil | ✅ | ✅ | ✅ | ✅ |
| Page Live | ✅ | ✅ | ✅ | ✅ |
| Consulter le journal | ❌ | ✅ | ✅ | ✅ |
| Créer / modifier / supprimer une fiche | ❌ | ❌ | ✅ (ses fiches) | ✅ |
| Ajouter / modifier un individu | ❌ | ❌ | ✅ | ✅ |
| Gérer les catégories | ❌ | ❌ | ✅ | ✅ |
| Profil personnel | ✅ | ✅ | ✅ | — |
| Gestion des utilisateurs | ❌ | ❌ | ❌ | ✅ |
| Configuration Webcam | ❌ | ❌ | ❌ | ✅ |
| Configuration mail SMTP | ❌ | ❌ | ❌ | ✅ |

### Règles importantes

- Un Adhérent dont l'abonnement expire est **automatiquement rétrogradé Invité** à sa prochaine connexion et hérite de la durée d'accès invité par défaut.
- Un Adhérent a accès au journal en **lecture seule** — il ne peut pas créer, modifier ou supprimer de fiche.
- L'administrateur se connecte via une **URL secrète distincte** — il ne passe pas par `/login`.
- `Guard::requireRole(ROLE_X)` est **toujours la première instruction** du constructeur de chaque contrôleur.

---

## 11. Maintenance

### 11.1 Activer les erreurs (développement uniquement)

Dans `app/param.php` :
```php
define('DB_DEBUG',   true);  // affiche les erreurs PDO
define('AJAX_DEBUG', true);  // popup navigateur sur les appels AJAX
```

Dans `index.php` :
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

### 11.2 Déboguer une variable PHP

```php
debug($section);            // affiche l'objet
debug($_SESSION, $_POST);   // plusieurs variables
dd($user);                  // affiche et stoppe l'exécution
```

### 11.3 Réinitialiser un mot de passe en base

Générer d'abord le hash BCrypt (via PHP) :
```php
echo password_hash('nouveau_mot_de_passe', PASSWORD_BCRYPT);
```

Puis mettre à jour en base :
```sql
UPDATE User
SET password = '$2y$10$...'
WHERE email = 'utilisateur@example.com';
```

### 11.4 Sauvegarder la base de données

```bash
mysqldump -u root -p KTSF > backup_$(date +%Y%m%d).sql
```

### 11.5 Restaurer une sauvegarde

```bash
mysql -u root -p KTSF < backup_20260101.sql
```

### 11.6 Ajouter une route

Dans `app/route/Routing.php` :
```php
$route->add('/ma-page', 'controleur\MaPage');
```

Puis créer `controleur/MaPage.php` avec `Guard::requireRole(...)` **en première ligne** du constructeur.

### 11.7 Ajouter une colonne en base

1. `ALTER TABLE` sur la BDD
2. Getter/setter dans `modele/Xxx.php`
3. Mise à jour du DAO (`modele/DAO/XxxDAO.php`)
4. Mise à jour de la vue (`vue/xxx.php`)

### 11.8 Routes à désactiver avant mise en production

Dans `app/route/Routing.php`, **commenter** les lignes suivantes :

```php
// $route->add('/phpinfo', function () { phpinfo(); });  // expose la config serveur
// $route->add('/dev/login', 'controleur\DevLogin');     // connexion automatique dev
```

---

## 12. Structure des répertoires

```
Amikiro-php/
├── app/                         # Noyau applicatif
│   ├── autoload.php             # Autoloader PSR-0 maison
│   ├── const.php                # Constantes globales (chemins, URL_ADMIN)
│   ├── route/Routing.php        # Déclaration et dispatch des routes
│   ├── util/
│   │   ├── Guard.php            # Contrôle d'accès (rôles)
│   │   ├── Request.php          # Accès sécurisé à $_GET / $_POST
│   │   ├── SessionLogin.php     # Gestion de session et authentification
│   │   ├── Mailer.php           # Envoi email via PHPMailer (lit Config en BDD)
│   │   └── BaseURL.php          # Génération des URLs absolues
│   ├── DB.php                   # ⚠ Gitignored — à créer manuellement
│   └── param.php                # ⚠ Gitignored — à créer manuellement
│
├── controleur/
│   ├── MainAjax.php             # Point d'entrée unique AJAX
│   ├── Live.php                 # Flux vidéo en direct
│   ├── admin/
│   │   ├── AdminControleur.php  # Portail de connexion administrateur
│   │   ├── Users.php            # Gestion des utilisateurs
│   │   ├── Webcam.php           # Configuration flux vidéo
│   │   └── MailConfig.php       # Configuration SMTP
│   ├── common/                  # Accueil, Login, Logout, NotFound
│   └── journal/                 # Journal, fiches, catégories
│
├── modele/
│   ├── DAO/base/Database.php    # Classe CRUD générique (PDO)
│   ├── DAO/ConfigDAO.php        # Config applicative (webcam + SMTP)
│   ├── DAO/UserDAO.php
│   └── DAO/journalDAO/          # SectionDAO, BatDAO, CategoryDAO…
│
├── vue/
│   ├── common/header.php        # Navbar + ouverture <main>
│   ├── common/footer.php        # Fermeture </main> + <footer>
│   ├── admin/
│   │   ├── Users.php
│   │   ├── Webcam.php
│   │   └── MailConfig.php
│   └── journal/
│
├── asset/
│   ├── css/                     # main.css, login.css, live.css, admin.css…
│   ├── js/                      # main.js, live.js, tableau.js…
│   └── lib/                     # Bootstrap, jQuery, HLS.js, DataTables… (auto-hébergés)
│
├── KTSF.sql                     # Schéma + données initiales
├── migration_mail_config.sql    # Migration : colonnes SMTP dans Config
├── composer.json
├── index.php                    # Point d'entrée unique
└── .htaccess                    # Réécriture d'URL Apache
```
