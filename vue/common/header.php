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
    <title><?= htmlspecialchars($title) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= ASSET ?>/css/popup.css">
    <link rel="stylesheet" href="<?= ASSET ?>/css/main.css">
    <?= $customCSS ?>

    <script src="<?= ASSET ?>/js/popup.js" defer></script>
    <script src="<?= ASSET ?>/js/main.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
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
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a href="<?= $actual_link ?>live" class="nav-link nav-link-public">Live</a>
                    </li>

                    <?php if ($userRole >= ROLE_ADHERENT): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Journal</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <h6 class="dropdown-header">Consultation</h6>
                                </li>
                                <li><a class="dropdown-item" href="<?= $actual_link ?>journal">Consulter le journal</a></li>

                                <?php if ($userRole >= ROLE_NATURALISTE): ?>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <h6 class="dropdown-header">Gestion des données</h6>
                                    </li>
                                    <li><a class="dropdown-item" href="<?= $actual_link ?>journal/categorie">Édition Catégories</a></li>
                                    <li><a class="dropdown-item" href="<?= $actual_link ?>journal/fiche">Édition Fiches Chauve-Souris</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if (!\app\util\SessionLogin::isLogin()): ?>
                    <li class="nav-item">
                        <a href="<?= $actual_link ?>login" class="nav-link nav-link-public">Se connecter</a>
                    </li>
                    <?php endif; ?>

                    <?php if (\app\util\SessionLogin::isLogin()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Paramètres</a>
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
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
