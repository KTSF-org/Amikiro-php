<?php

/**
 * VUE : SectionBat.php
 */

?>

<div class="container">

    <p>
    Création fiche chauve-souris (formulaire avec les champs à remplir). </br>
    Ce menu s'affiche si l'urilisateur souhaite enregistrer une nouvelle chauve-souris
    </p>

    <div class="">

        <form method="POST" action="sectionBat">

            <div class="mb-3">
                <label for="name" class="form-label">Nom de la chauve-souris</label>
                <input type="text" class="form-control" id="batName" placeholder="Nom">
            </div>

            <div class="mb-3">
                <label for="birthDate" class="form-label">Date de naissance de la chauve-souris</label>
                <input type="text" class="form-control" id="batBirthDate" placeholder="Date de naissance">
            </div>

            <div class="mb-3">
                <label for="sex" class="form-label">Sexe de la chauve-souris</label>
                </br>
                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off">
                    <label class="btn btn-outline-primary" for="btnradio1">Femelle</label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                    <label class="btn btn-outline-primary" for="btnradio2">Mâle</label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
                    <label class="btn btn-outline-primary" for="btnradio3">Inconnu</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="weight" class="form-label">Masse de la chauve-souris</label>
                <input type="text" class="form-control" id="batWeight" placeholder="Masse">
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Autres notes à propos de la chauve-souris</label>
                <textarea class="form-control" id="batNotes" rows="3" placeholder="Notes"></textarea>
            </div>

            <button type="button" class="btn btn-primary">Enregistrer la chauve-souris</button>

        </form>
    </div>

</div>