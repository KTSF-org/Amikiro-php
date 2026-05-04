<?php

/**
 * VUE : SectionColony.php
 */
use app\util\Helper;
?>

<div class="container">

    <p>Création fiche colonie</p>

    <?php if ($modif): ?>
        <form id="colonyFormModif" method="POST" action="<?= $actual_link ?>sectionColony">
    <?php else: ?>
        <form id="colonyForm" method="POST" action="<?= $actual_link ?>sectionColony"></form>
    <?php endif; ?>

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
            <?php if ($modif): ?>
            <textarea class="form-control" id="observation" rows="3" placeholder="" name="colonyNotes"><?= $section->getContent() ?></textarea>
            <?php else: ?>
            <textarea class="form-control" id="observation" rows="3" placeholder="" name="colonyNotes"></textarea>
            <?php endif; ?>
        </div>
        <span id="formMessage"></span>
        <div class="mb-3 col-3">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </div>

    </form>



</div>
<script>
    $(document).ready(function () {


         $("#colonyFormModif").on("submit", function (e) {
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



    })
</script>
