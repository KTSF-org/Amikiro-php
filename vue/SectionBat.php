<?php

/**
 * VUE : SectionBat.php
 */

?>

<div class="container">

    <p>
        Création fiche chauve-souris (formulaire avec les champs à remplir). </br>
        Ce menu s'affiche si l'utilisateur souhaite enregistrer une nouvelle chauve-souris
    </p>

    <form method="post" action="SectionBat">
        <div class="row align-items-start">

            <div class="col">

                <div class="mb-3">
                    <label for="title" class="form-label">Titre de la rubrique</label>
                    <input type="text" class="form-control" id="sectionTitle" placeholder="Titre" name="sectionTitle">
                </div>

                <div class="mb-3">
                    <label for="observation" class="form-label">Observations</label>
                    <textarea class="form-control" id="sectionObservation" rows="3" placeholder=""
                        name="batNotes"></textarea>
                </div>
            </div>

            <div class="col">
                <div>
                    Liste des chauve-souris
                    <a href="<?= $url ?>" role="button" class="btn btn-primary">
                        Ajouter une nouvelle chauve-souris
                    </a>
                </div>
                <div>
                    <!-- TODO liste des bat de la bdd -->
                    <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                        <label class="btn btn-outline-primary" for="btnradio1">Bat 1</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                        <label class="btn btn-outline-primary" for="btnradio2">Bat 2</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
                        <label class="btn btn-outline-primary" for="btnradio3">Bat 3</label>
                    </div>
                </div>

            </div>

        </div>
    </form>



</div>