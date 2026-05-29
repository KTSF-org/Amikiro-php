<?php

use app\util\Helper;

/**
 * VUE : SectionBatAddition.php
 * Variables reçues :
 *   $modif      — bool       : true = modification, false = ajout
 *   $bat        — Bat        : chauve-souris existante (si $modif)
 *   $allSpecies — Species[]  : liste complète des espèces
 */
?>
<script src="asset/js/formulaire.js" defer></script>
<link rel="stylesheet" href="<?= ASSET ?>/css/section.css">

<div class="container py-4">

    <div class="mb-3">
        <a href="sectionBat" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-chevron-left me-1"></i>Retour à la fiche
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="card">
                <div class="card-header bg-dark text-white py-2">
                    <span class="fw-semibold small">
                        <?= $modif ? 'Modifier la chauve-souris' : 'Ajouter une chauve-souris' ?>
                    </span>
                </div>
                <div class="card-body">

                    <?php if ($modif): ?>
                        <form method="post" id="formulaire"
                              action="sectionBatAddition?bat=mod&id=<?= $bat->getId() ?>">
                    <?php else: ?>
                        <form method="post" id="formulaire" action="sectionBatAddition?bat=add">
                    <?php endif; ?>

                        <div class="mb-3">
                            <label for="batName" class="form-label">
                                Nom
                            </label>
                            <input type="text" class="form-control mandatory" id="batName"
                                   placeholder="Nom de la chauve-souris" name="batName"
                                   <?php if ($modif) echo 'value="' . htmlspecialchars($bat->getName()) . '"'; ?>>
                        </div>

                        <div class="mb-3">
                            <label for="floatingSelect" class="form-label">
                                Espèce
                            </label>
                            <select class="form-select mandatory" id="floatingSelect" name="batSpecies">
                                <?php foreach ($allSpecies as $species):
                                    $selected = ($modif && $species->getId() == $bat->getIdSpecies()) ? 'selected' : '';
                                ?>
                                    <option value="<?= $species->getId() ?>" <?= $selected ?>>
                                        <?= htmlspecialchars($species->getCommonName()) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="batBirthDate" class="form-label">Date de naissance</label>
                            <input type="datetime-local" class="form-control mandatory"
                                   id="batBirthDate" name="batBirthDate"
                                   <?php if ($modif) echo "value='" . Helper::dateToDatetimelocal($bat->getBirthDate()) . "'"; ?>>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sexe</label>
                            <div class="btn-group d-flex" role="group" aria-label="Sélection du sexe">
                                <input type="radio" class="btn-check" id="btnradio1"
                                       autocomplete="off" name="batSex" value="1"
                                       <?php if ($modif && $bat->getSex() == 1) echo 'checked'; ?>>
                                <label class="btn btn-outline-primary flex-fill" for="btnradio1">Femelle</label>

                                <input type="radio" class="btn-check" id="btnradio2"
                                       autocomplete="off" name="batSex" value="2"
                                       <?php if ($modif && $bat->getSex() == 2) echo 'checked'; ?>>
                                <label class="btn btn-outline-primary flex-fill" for="btnradio2">Mâle</label>

                                <input type="radio" class="btn-check" id="btnradio3"
                                       autocomplete="off" name="batSex" value="0"
                                       <?php if ($modif && $bat->getSex() == 0 || !$modif) echo 'checked'; ?>>
                                <label class="btn btn-outline-primary flex-fill" for="btnradio3">Inconnu</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="batWeight" class="form-label">Masse (grammes)</label>
                            <input type="text" class="form-control mandatory" id="batWeight"
                                   placeholder="Ex : 12.5" name="batWeight"
                                   <?php if ($modif) echo 'value="' . htmlspecialchars($bat->getWeight()) . '"'; ?>>
                        </div>

                        <div class="mb-3">
                            <label for="batNotes" class="form-label">Notes</label>
                            <textarea class="form-control mandatory" id="batNotes"
                                      rows="3" name="batNotes"><?php
                                if ($modif) echo htmlspecialchars($bat->getNote());
                            ?></textarea>
                        </div>

                        <button type="submit" class="btn px-4" style="background-color: #3C5060; color: white">
                            <?= $modif ? 'Modifier' : 'Enregistrer' ?> la chauve-souris
                        </button>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
