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

    <form method="post" action="sectionBat">
        <div class="row align-items-start">

            <div class="col">

                <div class="mb-3">
                    <label for="title" class="form-label">Titre de la rubrique</label>
                    <input type="text" class="form-control" id="sectionTitle" placeholder="Titre" name="sectionTitle">
                </div>

                <div class="mb-3">
                    <label for="observation" class="form-label">Observations</label>
                    <textarea class="form-control" id="sectionObservation" rows="3" placeholder=""
                        name="sectionObservation"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer la fiche</button>
            </div>

            <div class="col">
                <div>
                    Liste des chauve-souris
                    <a href="<?= $url ?>" role="button" class="btn btn-primary">
                        Ajouter une nouvelle chauve-souris
                    </a>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-1 text-center border">
                            -
                        </div>
                        <div class="col-2 text-center border">
                            #
                        </div>
                        <div class="col border">
                            Nom
                        </div>
                        <div class="col-2 border">
                            Détails
                        </div>
                    </div>
                    <?= $batList ?>
                    <?= $batDetailsModals ?>
                </div>

            </div>

        </div>
    </form>



</div>