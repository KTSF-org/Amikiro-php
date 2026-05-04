<?php

use app\util\Helper;

/**
 * VUE : SectionBatAddition.php
 */

?>
<script src="asset/js/formulaire.js" defer></script>
<div class="container-fluid">

    <a href="sectionBat" role="button" class="btn btn-primary m-1">Retour</a>

    <?php if ($modif) { ?>
        <form method="post" action="sectionBatAddition?bat=mod&id=<?= $bat->getId() ?>">
        <?php } else { ?>
        <form method="post" action="sectionBatAddition?bat=add">
        <?php } ?>


        <div class="mb-3">
            <label for="name" class="form-label">Nom de la chauve-souris</label>
            <input type="text" class="form-control" id="batName" placeholder="Nom" name="batName" <?php if ($modif) {
                echo 'value="' . $bat->getName() . '"';
            } ?>>
        </div>

        <div class="mb-3">
            <div class="form-floating">
                <select class="form-select" id="floatingSelect" aria-label="Floating label select example"
                    name="batSpecies">
                    <?php
                    $speciesOption = "";
                    foreach ($allSpecies as $species) {
                        $speciesOption .= "<option ";
                        if ($modif && $species->getId() == $bat->getIdSpecies())
                            $speciesOption .= "selected ";
                        $speciesOption .= "value='" . $species->getId() . "'>" .
                            $species->getCommonName() . "</option>";
                    }
                    echo $speciesOption;
                    ?>
                </select>
                <label for="floatingSelect">Espèce de la chauve-souris</label>
            </div>
        </div>

        <div class="mb-3">
            <label for="birthDate" class="form-label">Date de naissance de la chauve-souris</label>
            </br>
            <input type="datetime-local" id="batBirthDate" name="batBirthDate" <?php if ($modif) {
                echo "value='" . Helper::dateToDatetimelocal($bat->getBirthDate()) . "'";
            } ?> >
        </div>

        <div class="mb-3">
            <label for="sex" class="form-label">Sexe de la chauve-souris</label>
            </br>
            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                <input type="radio" class="btn-check" id="btnradio1" autocomplete="off" name="batSex" value="1"
                <?php if ($modif && $bat->getSex() == 1) echo "checked";?>>
                <label class="btn btn-outline-primary" for="btnradio1">Femelle</label>

                <input type="radio" class="btn-check" id="btnradio2" autocomplete="off" name="batSex" value="2"
                <?php if ($modif && $bat->getSex() == 2) echo "checked";?>>
                <label class="btn btn-outline-primary" for="btnradio2">Mâle</label>

                <input type="radio" class="btn-check" id="btnradio3" autocomplete="off" name="batSex" value="0"
                <?php if ($modif && $bat->getSex() == 0) echo "checked";?>>
                <label class="btn btn-outline-primary" for="btnradio3">Inconnu</label>
            </div>
        </div>

        <div class="mb-3">
            <label for="weight" class="form-label">Masse de la chauve-souris</label>
            <input type="text" class="form-control" id="batWeight" placeholder="Masse" name="batWeight" <?php if ($modif) {
                echo 'value="' . $bat->getWeight() . '"';
            } ?>>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Autres notes à propos de la chauve-souris</label>
            <textarea class="form-control" id="batNotes" rows="3" placeholder="Notes" name="batNotes"><?php if ($modif) {
                echo $bat->getNote();
            } ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary" id="envoyer">
             <?php if ($modif) echo "Modifier"; else echo "Enregistrer"; ?> la chauve-souris</button>
    </form>
</div>
