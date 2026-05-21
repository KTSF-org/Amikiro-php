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
    <link rel="icon" href="<?= ASSET ?>/img/favicon.ico" type="image/x-icon" />
    <title><?= $title ?></title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="asset/lib/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= ASSET ?>/css/popup.css">
    <link rel="stylesheet" href="<?= ASSET ?>/css/main.css">
    <?= $customCSS ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="<?= ASSET ?>/js/popup.js" defer></script>
    <script src="<?= ASSET ?>/js/main.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="<?= ASSET ?>/js/<?= $_SESSION['CUSTOM_JS'] ?>" defer></script>
    <?= $customJS ?>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= $actual_link ?>accueil">
                <div class="app-logo"><?= htmlspecialchars(MAIN_TITLE) ?></div>
            </a>

            <div class="d-flex align-items-center">
                <?php if (\app\util\SessionLogin::isLogin()): ?>
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="btn btn-danger" href="<?= $actual_link ?>live" role="button">Live</a>
                    </li>

                    <?php if ($userRole >= ROLE_ADHERENT): ?>
                        <li class="nav-item dropdown">
                            <!-- <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Journal</a> -->
                             <button class="btn btn-outline-info dropdown-toggle mx-2" type="button" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Journal</button>
                            <ul class="dropdown-menu">
                                <li>
                                    <h6 class="dropdown-header">Consultation</h6>
                                </li>
                                <li><a class="dropdown-item" href="<?= $actual_link ?>journal">Consulter le journal</a></li>

                                <?php if ($userRole >= ROLE_NATURALISTE): ?>
                                    <li><a class="dropdown-item" href="<?= $actual_link ?>journal?mesFiches=true"> Mes fiches</a></li>
                                    <li><a class="dropdown-item" href="<?= $actual_link ?>category"> Catégories</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item dropdown">
                        <!-- <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Paramètres</a> -->
                        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Paramètres</button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <h6 class="dropdown-header">Compte</h6>
                            </li>
                            <li><a class="dropdown-item" href="<?= $actual_link ?>parametres/profil">Mon Profil</a></li>
                            <?php if ($userRole === ROLE_ADMIN): ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <h6 class="dropdown-header">Administration</h6>
                                </li>
                                <li><a class="dropdown-item" href="<?= $actual_link ?>parametres/utilisateurs">Gestion des utilisateurs</a></li>
                                <li><a class="dropdown-item" href="<?= $actual_link ?>parametres/webcam">Configuration Webcam</a></li>
                            <?php endif; ?>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="<?= $actual_link ?>logout">Déconnexion</a></li>
                        </ul>
                    </li>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>
