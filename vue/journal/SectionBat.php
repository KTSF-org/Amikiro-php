<?php

use modele\DAO\journalDAO\SpeciesDAO;

/**
 * VUE : SectionBat.php
 */

?>

<div class="container">

    <form method="post" action="sectionBat">
        <div class="row align-items-start mt-5">

            <div class="col">

                <div class="mb-3">
                    <label for="title" class="form-label">Titre de la rubrique</label>
                    <input type="text" class="form-control" id="sectionTitle" placeholder="Titre" name="sectionTitle">
                </div>

                <div class="mb-3">
                    <label for="Date" class="form-label">Date</label>
                    <input type="datetime-local" class="" id="date" name="colonyDate">
                </div>

                <div class="mb-3">
                    <label for="observation" class="form-label">Observations</label>
                    <textarea class="form-control" id="sectionObservation" rows="6" placeholder=""
                        name="sectionObservation"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer la fiche</button>
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
                        $id = $bat->getId();
                        $name = $bat->getName();
                        $species = $speciesList[$bat->getIdSpecies()];
                        $sex = $sexList[$bat->getSex()];
                        $details = '
                    <button type="button" class="btn btn-sm"
                    data-bs-toggle="modal" data-bs-target="#modal' . $id . '">
                        <i class="bi bi-eye-fill" width="20px" height="20px"></i>
                    </button>
                    <a href="' . $urlModif . '&id=' . $id . '" style="color:black">
                        <i class="bi bi-pencil-square" width="20" height="20"></i>
                    </a>
                    <a href="' . $urlDelete . '&id=' . $id . '" style="color:black">
                        <i class="bi bi-trash3" width="20" height="20"></i>
                    </a>
                    ';
                        echo "
                    <div class='row'>
                        <div class='col-1 text-center border'>
                            <input type='radio' class='form-check-input' name='batSelected'>
                         </div>
                        <div class='col-2 text-center border'>" . $id . "</div>
                        <div class='col border'>" . $name . "</div>
                     <div class='col-2 border'>" . $details . "</div>
                    </div>";
                        echo '
                    <div class="modal fade" id="modal' . $id . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5">' . $name . '</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Espece : ' . $species . '<br/>
                                    Date de naissance : ' . $bat->getBirthDate() . '<br/>
                                    Sexe : ' . $sex . '<br/>
                                    Poids : ' . $bat->getWeight() . ' grammes<br/><br/>
                                    Note :<br/> ' . $bat->getNote() . '
                                </div>
                            </div>
                        </div>
                    </div>';
                    }
                    ?>
                </div>

                <a href="<?= $urlAdd ?>" role="button" class="btn btn-primary">
                    Ajouter une nouvelle chauve-souris
                </a>

            </div>
        </div>
    </form>



</div>