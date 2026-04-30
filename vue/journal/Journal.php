<?php

/**
 * VUE : Journal.php
 */

?>

<div class="container">

    <h1 class="m-2"> Journal </h1>

    <div class="col m-2">
        <div class="container">
            <div class="row">
                <div class="col-1 text-center border">
                    #
                </div>
                <div class="col border">
                    Type
                </div>
                <div class="col border">
                    Titre
                </div>
                <div class="col border">
                    DATE / HEURE
                </div>
                <div class="col-2 border">
                    Auteur
                </div>
                <div class="col-1 text-center border">
                    -
                </div>
            </div>
            <?php foreach($listFiches as $fiche){ ?>
            <div class="row">
                <div class="col-1 text-center border">
                    <?=$fiche->getId()?>
                </div>
                <div class="col border">
                    <?= $typeAsso[$fiche->getId()] ?>
                </div>
                <div class="col border">
                    <?=$fiche->getTitle()?>
                </div>
                <div class="col border">
                    <?=$fiche->getCreationDate()?>
                </div>
                <div class="col-2 border">
                    <?= $usersAsso[$fiche->getIdUser()]?>
                </div>
                <div class="col-1 text-center border">
                    -
                </div>
            </div>
            <?php }?>
        </div>
    </div>
</div>