<?php

/**
 * VUE : SectionColony.php
 * Variables reçues :
 *   $modif      — bool           : true = modification, false = création
 *   $section    — Section        : fiche existante (si $modif)
 *   $catSec     — Category       : catégorie associée (si $modif)
 *   $sectionId  — int            : id de la section (si $modif, pour l'AJAX)
 *   $categories — string         : options <option> HTML (si création)
 */
use app\util\Helper;
?>
<link href="<?= ASSET ?>/lib/tom-select/css/tom-select.css" rel="stylesheet">
<script src="<?= ASSET ?>/lib/tom-select/js/tom-select.complete.min.js"></script>
<script src="asset/js/formulaire.js" defer></script>

<div class="container py-4">

    <div class="mb-3">
        <a href="journal" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-chevron-left me-1"></i>Retour au journal
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">

            <div class="card">
                <div class="card-header bg-dark text-white py-2">
                    <span class="fw-semibold small">
                        <?= $modif ? 'Modifier la fiche colonie' : 'Nouvelle fiche colonie' ?>
                    </span>
                </div>
                <div class="card-body">

                    <?php if ($modif): ?>
                        <form id="formulaire" class="colonyFormModif" method="POST"
                            action="<?= $actual_link ?>sectionColony">
                        <?php else: ?>
                            <form id="formulaire" class="colonyForm" method="POST"
                                action="<?= $actual_link ?>sectionColony">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    Titre <span class="text-danger">*</span>
                                </label>
                                <?php if ($modif): ?>
                                    <input type="text" class="form-control mandatory" id="title" name="colonyTitle"
                                        value="<?= htmlspecialchars($section->getTitle()) ?>">
                                <?php else: ?>
                                    <input type="text" class="form-control mandatory" id="title"
                                        placeholder="Titre de la rubrique" name="colonyTitle">
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <?php if ($modif): ?>
                                    <input type="datetime-local" class="form-control" id="date" name="colonyDate"
                                        value="<?= Helper::dateToDatetimelocal($section->getEventDate()) ?>">
                                <?php else: ?>
                                    <input type="datetime-local" class="form-control" id="date" name="colonyDate">
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">
                                    Catégorie <span class="text-danger">*</span>
                                </label>
                                <!-- Tom Select initialise ce <select> — la classe form-select s'applique avant init -->
                                <select class="form-select mandatory" id="category" name="colonyCategory">
                                    <option value="">— Choisissez une catégorie —</option>
                                    <?= $categories ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="observation" class="form-label">Observations</label>
                                <?php if ($modif): ?>
                                    <textarea class="form-control mandatory" id="observation" rows="4"
                                        name="colonyNotes"><?= htmlspecialchars($section->getContent()) ?></textarea>
                                <?php else: ?>
                                    <textarea class="form-control mandatory" id="observation" rows="4"
                                        name="colonyNotes"></textarea>
                                <?php endif; ?>
                            </div>

                            <!-- Message de retour AJAX -->
                            <p id="formMessage" class="form-feedback mb-3"></p>

                            <button type="submit" class="btn btn-primary px-4"><?= $modif ? "Modifier" : "Enregistrer"?></button>

                        </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        // Tom Select sur la liste de catégories
        new TomSelect('#category', {
            create: true,
            sortField: { field: 'text' }
        });

        // Formulaire de MODIFICATION (AJAX)
        $('.colonyFormModif').on('submit', function (e) {
            e.preventDefault();
            const $msg = $('#formMessage').removeClass('success error');

            const url = '<?= $actual_link ?>ajax?updateSectionColony';
            const data = {
                title: $('#title').val(),
                date: $('#date').val(),
                category: $('#category').val(),
                notes: $('#observation').val(),
                sectionId: <?= (int) $sectionId ?>,
            };

            const request = new AjaxRequest(url, 'POST', data);
            request.send(
                (response) => {
                    if (response === 'Success') {
                        $msg.addClass('success').text('Rubrique modifiée avec succès.');
                    } else {
                        $msg.addClass('error').text('Erreur : veuillez renseigner tous les champs.');
                    }
                },
                () => { }
            );
        });

        // Formulaire de CRÉATION (AJAX)
        $('.colonyForm').on('submit', function (e) {
            e.preventDefault();
            const $msg = $('#formMessage').removeClass('success error');

            const url = '<?= $actual_link ?>ajax?addSectionColony';
            const data = {
                title: $('#title').val(),
                date: $('#date').val(),
                category: $('#category').val(),
                notes: $('#observation').val(),
            };

            const request = new AjaxRequest(url, 'POST', data);
            request.send(
                (response) => {
                    if (response === 'Success') {
                        $msg.addClass('success').text('Rubrique créée avec succès.');
                    } else {
                        $msg.addClass('error').text('Erreur : veuillez renseigner tous les champs.');
                    }
                },
                () => { }
            );
        });

    });
</script>
