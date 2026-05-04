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
            <a href="<?= $actual_link ?>live" class="btn btn-dark px-4">Accéder au live</a>
        </div>

        <hr class="accueil-sep my-4">

        <div class="row justify-content-center text-start">
            <div class="col-md-7">

                <section class="accueil-bloc mb-4">
                    <h2>Missions de conservation</h2>
                    <p>
                        L'association valorise ses connaissances liées aux chiroptères et leurs milieux naturels dans
                        des programmes d'actions, d'éducation et de sensibilisation à l'environnement auprès de tous
                        publics. Les animations nature sont adaptées pour être effectuées dans les locaux de
                        l'association, du prestataire ou à l'extérieur. Amikiro met à profit ses compétences sur
                        l'ensemble de la région Bretagne.
                    </p>
                </section>

                <section class="accueil-bloc">
                    <h2>Écosystème Numérique</h2>
                    <p>
                        L'association met en œuvre le développement, la gestion, l'animation et la valorisation
                        d'actions de sensibilisation avec ses partenaires sur le patrimoine naturel et culturel :
                        tourisme et biodiversité. Le but recherché est de maintenir un tissu économique et social
                        durable par la découverte du patrimoine tout en assurant la protection de la biodiversité. Au
                        travers de son programme de valorisation du patrimoine, l'association inaugure un projet
                        innovant visant au maintien d'actions socio-économiques grâce à la mise en réseau des acteurs.
                    </p>
                </section>

            </div>
        </div>

    </div>
</div>
