<?php

/**
 * VUE : COMMON : header.php
 */
// Mise en cache du rôle
$userRole = \app\util\SessionLogin::getRole();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= ASSET ?>/img/favicon.ico" type="image/x-icon">
    <title><?= $title ?></title>
    <!-- Bootstrap CSS (local) -->
    <link href="<?= ASSET ?>/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS (local) -->
    <link rel="stylesheet" href="<?= ASSET ?>/lib/datatables/jquery.dataTables.min.css">
    <!-- Bootstrap Icons (local — police et CSS auto-hébergés) -->
    <link rel="stylesheet" href="<?= ASSET ?>/lib/bootstrap-icons/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= ASSET ?>/css/popup.css">
    <link rel="stylesheet" href="<?= ASSET ?>/css/main.css">
    <?= $customCSS ?>

    <!-- Variable JS globale pour les fichiers .js purs (pas de PHP) -->
    <script>const ASSET_BASE = '<?= ASSET ?>';</script>
    <!-- jQuery (local) -->
    <script src="<?= ASSET ?>/lib/jquery/jquery.min.js"></script>
    <script src="<?= ASSET ?>/js/popup.js" defer></script>
    <script src="<?= ASSET ?>/js/main.js" defer></script>
    <!-- HLS.js (local) -->
    <script src="<?= ASSET ?>/lib/hlsjs/hls.min.js"></script>
    <!-- Bootstrap JS (local) -->
    <script src="<?= ASSET ?>/lib/bootstrap/js/bootstrap.bundle.min.js" defer></script>
    <!-- DataTables JS (local) -->
    <script src="<?= ASSET ?>/lib/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= ASSET ?>/js/<?= $_SESSION['CUSTOM_JS'] ?>" defer></script>
    <?= $customJS ?>
</head>

<body>
    <!--
        navbar-expand-lg : le menu se replie en hamburger sous lg (< 992 px).
        navbar-toggler + collapse#navbarMain : comportement responsive Bootstrap 5.
    -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">

            <a class="navbar-brand" href="<?= $actual_link ?>accueil">
                <div class="app-logo"><?= htmlspecialchars(MAIN_TITLE) ?></div>
            </a>

            <?php if (\app\util\SessionLogin::isLogin()): ?>

            <!-- Bouton hamburger — visible uniquement sous lg -->
            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarMain"
                    aria-controls="navbarMain" aria-expanded="false" aria-label="Ouvrir le menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Zone de navigation — collapse sur mobile, inline sur desktop -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto align-items-lg-center py-2 py-lg-0">

                    <li class="nav-item my-1 my-lg-0 me-lg-1">
                        <a class="btn" href="<?= $actual_link ?>live" style="background-color: #B03139; color: white">Live</a>
                    </li>

                    <?php if ($userRole >= ROLE_ADHERENT): ?>
                    <li class="nav-item dropdown my-1 my-lg-0 me-lg-1">
                        <button class="btn btn-outline-light dropdown-toggle"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Journal
                        </button>
                        <ul class="dropdown-menu dropdown-menu-lg-end">
                            <li><h6 class="dropdown-header">Consultation</h6></li>
                            <li><a class="dropdown-item" href="<?= $actual_link ?>journal">Consulter le journal</a></li>
                            <?php if ($userRole >= ROLE_NATURALISTE): ?>
                                <li><a class="dropdown-item" href="<?= $actual_link ?>journal?mesFiches=true">Mes fiches</a></li>
                                <li><a class="dropdown-item" href="<?= $actual_link ?>category">Catégories</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <li class="nav-item dropdown my-1 my-lg-0">
                        <button class="btn btn-outline-light dropdown-toggle"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Paramètres
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">Compte</h6></li>
                            <li><a class="dropdown-item" href="<?= $actual_link ?>parametres/profil">Mon Profil</a></li>
                            <?php if ($userRole === ROLE_ADMIN): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Administration</h6></li>
                                <li><a class="dropdown-item" href="<?= $actual_link ?>parametres/utilisateurs">Gestion des utilisateurs</a></li>
                                <li><a class="dropdown-item" href="<?= $actual_link ?>parametres/webcam">Configuration Webcam</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= $actual_link ?>logout">Déconnexion</a></li>
                        </ul>
                    </li>

                </ul>
            </div>

            <?php endif; ?>
        </div>
    </nav>

    <!--
        <main class="page-body"> enveloppe tout le contenu de la vue.
        flex: 1 dans main.css pousse naturellement le footer en bas de page,
        sans aucun JavaScript — sticky footer CSS pur.
    -->
    <main class="page-body">
