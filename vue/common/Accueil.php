<?php
/**
 * VUE : Accueil
 * Variables reçues :
 *   $name    — string : prénom de l'utilisateur connecté
 *   $surname — string : nom
 */
?>
<div class="accueil-hero">
    <div class="container text-center">

        <div class="accueil-logo">
            <img src="<?= ASSET ?>/img/app.png"
                 alt="Logo officiel Amikiro - Protection des chiroptères"
                 width="150" height="150" loading="lazy">
        </div>

        <h1 class="accueil-titre text-uppercase"><?= htmlspecialchars(MAIN_TITLE) ?></h1>
        <p class="accueil-sous-titre lead">Maison des Chauves-Souris</p>

        <p class="accueil-connecte">
            Bienvenue, <strong><?= htmlspecialchars($name . ' ' . $surname) ?></strong>
        </p>

        <div class="mt-4">
            <a href="<?= $actual_link ?>live" class="btn px-4" style="background-color: #B03139; color: white">
                <i class="bi bi-camera-video-fill me-2"></i>Accéder au live
            </a>
        </div>

        <hr class="accueil-sep">

        <!--
            Deux feature cards côte à côte (md+) → empilées sur mobile.
            Remplacent les anciens blocs de texte brut.
        -->
        <div class="row g-4 justify-content-center text-start">
            <div class="col-md-5">
                <div class="accueil-feature">
                    <div class="accueil-feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h2>Missions de conservation</h2>
                    <p>
                        L'association valorise ses connaissances liées aux chiroptères et leurs milieux naturels dans
                        des programmes d'actions, d'éducation et de sensibilisation à l'environnement auprès de tous
                        publics. Les animations nature sont adaptées pour être effectuées dans les locaux de
                        l'association, du prestataire ou à l'extérieur. Amikiro met à profit ses compétences sur
                        l'ensemble de la région Bretagne.
                    </p>
                </div>
            </div>
            <div class="col-md-5">
                <div class="accueil-feature">
                    <div class="accueil-feature-icon">
                        <i class="bi bi-diagram-3"></i>
                    </div>
                    <h2>Écosystème Numérique</h2>
                    <p>
                        L'association met en œuvre le développement, la gestion, l'animation et la valorisation
                        d'actions de sensibilisation avec ses partenaires sur le patrimoine naturel et culturel :
                        tourisme et biodiversité. Le but recherché est de maintenir un tissu économique et social
                        durable par la découverte du patrimoine tout en assurant la protection de la biodiversité.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
