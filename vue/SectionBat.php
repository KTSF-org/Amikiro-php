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

                <div class="mb-3">
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="sectionCategory">
                            <option selected>Catégorie</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                        <label for="floatingSelect">Works with selects</label>
                    </div>
                </div>
            </div>

            <div class="col">
                col 2
                <a href="<?= $url ?>" role="button" class="btn btn-primary">
                    Ajouter une nouvelle chauve-souris
                </a>
            </div>

        </div>
    </form>



</div>