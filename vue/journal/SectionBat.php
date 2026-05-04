<?php

use app\util\Helper;


/**
 * VUE : SectionBat.php
 */

?>

<script src="asset/js/formulaire.js" defer></script>

<div class="container">

    <a href="journal" role="button" class="btn btn-primary m-1">Retour</a>

    <?php if ($edit) { ?>
        <form method="post" id="formulaire" action="sectionBat?section=edited&id=<?= $section->getId() ?>">
    <?php } else { ?>
        <form method="post" id="formulaire" action="sectionBat">
    <?php } ?>

            <div class="row align-items-start mt-5">

                <div class="col">

                    <div class="mb-3">
                        <label for="title" class="form-label">Titre de la rubrique</label>
                        <input type="text" class="form-control mandatory" id="sectionTitle" placeholder="Titre"
                            name="sectionTitle" <?php if ($edit) {
                                echo 'value="' . $section->getTitle() . '"';
                            } ?>>
                    </div>

                    <div class="mb-3">
                        <label for="Date" class="form-label">Date</label>
                        <input type="datetime-local" class="mandatory" id="date" name="date"<?php if ($edit) {
                            echo "value='" . Helper::dateToDatetimelocal($section->getCreationDate()) . "'";
                        } ?> >
                    </div>

                    <div class="mb-3">
                        <label for="observation" class="form-label">Observations</label>
                        <textarea class="form-control mandatory" id="sectionObservation" rows="6" placeholder=""
                            name="sectionObservation"><?php if ($edit) {
                                echo $section->getContent();
                            } ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <?php if ($edit) echo "Modifier"; else echo "Enregistrer"; ?> la fiche</button>

                </div>

                <div class="col">

                    <div>
                        Liste des chauve-souris
                    </div>

                    <div class="container overflow-auto" style="max-height: 400px">
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
                        <?php
                        foreach ($batList as $bat) {

                            if ($edit)
                                $currentBatId = $sectionSpecimen->getIdBat();
                            $id = $bat->getId();
                            $name = $bat->getName();
                            $species = $speciesList[$bat->getIdSpecies()];
                            $sex = $sexList[$bat->getSex()];
                            ?>
                            <div class='row'>
                                <div class='col-1 text-center border'>
                                    <input type='radio' class='form-check-input' name='batSelected' value="<?= $id ?>"
                                    <?php if ($edit && $id == $currentBatId) echo " checked"; ?>>
                                </div>
                                <div class='col-2 text-center border'><?= $id ?></div>
                                <div class='col border'><?= $name ?></div>
                                <div class="col-2 border d-flex justify-content-around align-items-center p-0">
                                    <button type="button" class="btn btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modal<?= $id ?>" style="color:DodgerBlue">
                                        <i class="bi bi-eye-fill" width="20px" height="20px"></i>
                                    </button>
                                    <a href="<?= $urlModif ?>&id=<?= $id ?>" class="btn btn-sm" style="color:black">
                                        <i class="bi bi-pencil-square" width="20px" height="20px"></i>
                                    </a>
                                    <a href="<?= $urlDelete ?>&id=<?= $id ?>" class="btn btn-sm" style="color:red"
                                    onclick="return confirm ('Etes-vous sûr de vouloir supprimer cette chauve-souris ?');">
                                        <i class="bi bi-trash3" width="20px" height="20px"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="modal fade" id="modal<?= $id ?>" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5"><?= $name ?></h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Espece : <?= $species ?><br />
                                            Date de naissance : <?= $bat->getBirthDate() ?><br />
                                            Sexe : <?= $sex ?><br />
                                            Poids :<?= $bat->getWeight() ?> grammes<br /><br />
                                            Note : <br /> <?= $bat->getNote() ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <a href="<?= $urlAdd ?>" role="button" class="btn btn-primary">
                        Ajouter une nouvelle chauve-souris
                    </a>

                </div>
            </div>
        </form>



</div>
