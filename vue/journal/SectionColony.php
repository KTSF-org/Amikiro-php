<?php

/**
 * VUE : SectionColony.php
 */
use app\util\Helper;
?>
<?php var_dump($catSec) ?>
<?= $catSec->getName() ?>
<div class="container">

    <p>Création fiche colonie</p>

    <form id="colonyForm" method="POST" action="<?= $actual_link ?>sectionColony">

        <!-- Champs du formulaire -->
        <div class="mb-3 col-3">
            <label for="colonyTitle" class="form-label">Titre rubrique</label>
            <?php if ($modif): ?>
                <input type="text" class="form-control" id="title" value="<?= $section->getTitle() ?>" name="colonyTitle">
            <?php else: ?>
                <input type="text" class="form-control" id="title" placeholder="Titre rubrique" name="colonyTitle">
            <?php endif; ?>
        </div>
        <div class="mb-3 row col-8">
            <div class="mb-3 col-3">
                <label for="Date" class="form-label">Date</label>
                <?php if ($modif): ?>
                    <input type="datetime-local" class="" id="date" name="colonyDate"
                        value="<?= Helper::dateToDatetimelocal($section->getCreationDate()) ?>">
                <?php else: ?>
                    <input type="datetime-local" class="" id="date" name="colonyDate">
                <?php endif; ?>
            </div>
        </div>
        <div class="mb-3 col-3">
            <label for="colonyCategory" class="form-label">Catégorie</label>
            <select class="form-select" id="category" aria-label="Floating label select example" name="colonyCategory">
                <?php if ($modif): ?>
                    <option selected value="<?= $catSec->getId() ?>"><?= $catSec->getName() ?></option>
                <?php else: ?>
                    <option selected value="">----Choisissez une catégorie----</option>
                    <?= $categories ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="mb-3 col-3">
            <label for="colonyObservation" class="form-label">Observations</label>
            <textarea class="form-control" id="observation" rows="3" placeholder="" name="colonyNotes"></textarea>
        </div>
        <span id="formMessage"></span>
        <div class="mb-3 col-3">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </div>

    </form>



</div>
<script>
    $(document).ready(function () {

        if (<?php $modif ?>) {

         $("#colonyForm").on("submit", function (e) {
                e.preventDefault();

                const spanMessage = $('#formMessage');

                const url = "<?= $actual_link ?>ajax?updateSectionColony",

                    data = {
                        'title': $('#title').val(),
                        'date': $('#date').val(),
                        'category': $('#category').val(),
                        'notes': $('#observation').val(),
                        'sectionId' : <?= $sectionId ?>,
                    };

                const request = new AjaxRequest(url, 'POST', data);
                request.send(
                    //success
                    (response) => {
                        if (response === "Success") {
                            spanMessage.text('Rubrique créée avec succès').css('color', 'green');
                        } else {
                            spanMessage.text('Erreur lors de la création de la rubrique. Veuillez renseigner tout les champs').css('color', 'red');
                        }
                    }, (response) => {
                        spanMessage.text('Rubrique créée avec succès - complete').css('color', 'green');

                    }
                )


            });

        } else {

            $("#colonyForm").on("submit", function (e) {
                e.preventDefault();

                const spanMessage = $('#formMessage');

                const url = "<?= $actual_link ?>ajax?addSectionColony",

                    data = {
                        'title': $('#title').val(),
                        'date': $('#date').val(),
                        'category': $('#category').val(),
                        'notes': $('#observation').val(),
                    };

                const request = new AjaxRequest(url, 'POST', data);
                request.send(
                    //success
                    (response) => {
                        if (response === "Success") {
                            spanMessage.text('Rubrique créée avec succès').css('color', 'green');
                        } else {
                            spanMessage.text('Erreur lors de la création de la rubrique. Veuillez renseigner tout les champs').css('color', 'red');
                        }
                    }, (response) => {
                        spanMessage.text('Rubrique créée avec succès - complete').css('color', 'green');

                    }
                )


            });

        }

    })
</script>
