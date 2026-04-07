<?php
/**
 * VUE : Accueil.php
 */
// Sécurisation de la variable utilisateur
$currentUser = $user ?? null;
?>
<div class="accueil-hero">
    <div class="container text-center">

        <div class="accueil-logo">
            <img src="<?= ASSET ?>/img/app.png"
                 alt="Logo officiel Amikiro - Protection des chiroptères"
                 width="150"
                 height="150"
                 loading="lazy" />
        </div>

        <h1 class="accueil-titre text-uppercase"><?= htmlspecialchars(MAIN_TITLE) ?></h1>
        <p class="accueil-sous-titre lead">Maison des Chauves-Souris</p>

        <?php if (!empty($currentUser)): ?>
            <p class="accueil-connecte">
                Bienvenue, <strong><?= htmlspecialchars($currentUser->name . ' ' . $currentUser->surname) ?></strong>
            </p>
        <?php endif; ?>

        <hr class="accueil-sep my-4" />

        <div class="row justify-content-center text-start">
            <div class="col-md-7">
                <section class="accueil-bloc mb-4">
                    <h2>Missions de conservation</h2>
                    <p>
                        Le centre étudie et protège les chiroptères en milieu contrôlé. Scientifiques et bénévoles
                        y collaborent pour préserver ces espèces essentielles à l'équilibre de nos écosystèmes.
                    </p>
                </section>

                <section class="accueil-bloc">
                    <h2>Écosystème Numérique</h2>
                    <p>
                        Amikiro centralise le suivi biologique, la gestion des colonies et le streaming vidéo
                        en direct. Accès strictement réservé au personnel et aux collaborateurs.
                    </p>
                </section>
            </div>
        </div>

    </div>
</div>
