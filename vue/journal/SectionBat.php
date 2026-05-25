<?php

use app\util\Helper;

/**
 * VUE : SectionBat.php
 * Variables reçues :
 *   $edit            — bool             : vrai si on modifie une fiche existante
 *   $section         — Section          : fiche existante (si $edit)
 *   $sectionSpecimen — SectionSpecimen  : association section-chauve-souris (si $edit)
 *   $batList         — Bat[]            : liste de toutes les chauves-souris
 *   $speciesList     — array            : id → nom commun espèce
 *   $sexList         — array            : code → libellé sexe
 *   $urlModif        — string           : URL de modification d'une chauve-souris
 *   $urlDelete       — string           : URL de suppression d'une chauve-souris
 *   $urlAdd          — string           : URL d'ajout d'une chauve-souris
 */
?>

<script src="asset/js/formulaire.js" defer></script>
<script src="asset/js/tableau.js" defer></script>

<div class="container py-4">

    <div class="mb-3">
        <a href="journal" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-chevron-left me-1"></i>Retour au journal
        </a>
    </div>

    <?php if ($edit): ?>
        <form method="post" id="formulaire"
              action="sectionBat?section=edited&id=<?= $section->getId() ?>">
    <?php else: ?>
        <form method="post" id="formulaire" action="sectionBat">
    <?php endif; ?>

        <div class="row g-4">

            <!-- Colonne gauche : champs de la fiche -->
            <div class="col-lg-5">

                <div class="card">
                    <div class="card-header bg-dark text-white py-2">
                        <span class="fw-semibold small">
                            <?= $edit ? 'Modifier la fiche' : 'Nouvelle fiche individu' ?>
                        </span>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label for="sectionTitle" class="form-label">
                                Titre <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control mandatory" id="sectionTitle"
                                   placeholder="Titre de la fiche" name="sectionTitle"
                                   <?php if ($edit) echo 'value="' . htmlspecialchars($section->getTitle()) . '"'; ?>>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="datetime-local" class="form-control mandatory" id="date" name="date"
                                   <?php if ($edit) echo "value='" . Helper::dateToDatetimelocal($section->getEventDate()) . "'"; ?>>
                        </div>

                        <div class="mb-3">
                            <label for="sectionObservation" class="form-label">Observations</label>
                            <textarea class="form-control mandatory" id="sectionObservation"
                                      rows="6" name="sectionObservation"><?php
                                if ($edit) echo htmlspecialchars($section->getContent());
                            ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <?= $edit ? 'Modifier' : 'Enregistrer' ?> la fiche
                        </button>

                    </div>
                </div>

            </div>

            <!-- Colonne droite : liste des chauves-souris -->
            <div class="col-lg-7">

                <div class="card">
                    <div class="card-header bg-dark text-white py-2 d-flex justify-content-between align-items-center">
                        <span class="fw-semibold small">Chauves-souris</span>
                        <a href="<?= $urlAdd ?>" class="btn btn-sm btn-outline-light">
                            <i class="bi bi-plus-lg me-1"></i>Ajouter
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="listBat" class="table table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 36px;">–</th>
                                        <th>#</th>
                                        <th>Nom</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($batList as $bat):
                                        if ($edit) $currentBatId = $sectionSpecimen->getIdBat();
                                        $id      = $bat->getId();
                                        $name    = $bat->getName();
                                        $species = $speciesList[$bat->getIdSpecies()];
                                        $sex     = $sexList[$bat->getSex()];
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="radio" class="form-check-input" name="batSelected"
                                                   value="<?= $id ?>"
                                                   <?php if ($edit && $id == $currentBatId) echo 'checked'; ?>>
                                        </td>
                                        <td class="text-muted small"><?= $id ?></td>
                                        <td><?= htmlspecialchars($name) ?></td>
                                        <td class="text-end">
                                            <!-- Détail en modale -->
                                            <button type="button"
                                                    class="btn btn-sm btn-link p-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalBat<?= $id ?>"
                                                    title="Détail">
                                                <i class="bi bi-eye-fill icon-view fs-5"></i>
                                            </button>
                                            <!-- Modifier -->
                                            <a href="<?= $urlModif ?>&id=<?= $id ?>"
                                               class="btn btn-sm btn-link p-1" title="Modifier">
                                                <i class="bi bi-pencil-square icon-edit fs-5"></i>
                                            </a>
                                            <!-- Supprimer -->
                                            <a href="<?= $urlDelete ?>&id=<?= $id ?>"
                                               class="btn btn-sm btn-link p-1" title="Supprimer"
                                               onclick="return confirm('Supprimer cette chauve-souris ?');">
                                                <i class="bi bi-trash3 icon-delete fs-5"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </form>

</div>

<!--
    Les modales sont placées ICI, en dehors de la table et de la form.
    Mettre des <div> directement dans <tbody> est du HTML invalide et
    peut perturber DataTables et certains navigateurs.
-->
<?php foreach ($batList as $bat):
    $id      = $bat->getId();
    $name    = $bat->getName();
    $species = $speciesList[$bat->getIdSpecies()];
    $sex     = $sexList[$bat->getSex()];
?>
<div class="modal fade" id="modalBat<?= $id ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><?= htmlspecialchars($name) ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <dl class="row mb-0 small">
                    <dt class="col-sm-5 text-muted fw-normal">Espèce</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($species) ?></dd>

                    <dt class="col-sm-5 text-muted fw-normal">Date de naissance</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($bat->getBirthDate()) ?></dd>

                    <dt class="col-sm-5 text-muted fw-normal">Sexe</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($sex) ?></dd>

                    <dt class="col-sm-5 text-muted fw-normal">Masse</dt>
                    <dd class="col-sm-7"><?= htmlspecialchars($bat->getWeight()) ?> g</dd>

                    <?php if ($bat->getNote()): ?>
                    <dt class="col-sm-5 text-muted fw-normal">Notes</dt>
                    <dd class="col-sm-7"><?= nl2br(htmlspecialchars($bat->getNote())) ?></dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
