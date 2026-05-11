<?php

/**
 * VUE : Journal.php
 */

?>

<div class="container">

    <h1 class="m-2"><?=($mesFiches) ? 'Mes fiches' : 'Journal'?></h1>

    <div class="col m-2">
        <div class="container overflow-auto">
            <div class="row fw-bold">
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
            <?php foreach ($listFiches as $fiche) { ?>
                <div class="row">
                    <div class="col-1 text-center border">
                        <?= $fiche->getId() ?>
                    </div>
                    <div class="col border">
                        <?= $typeAsso[$fiche->getId()] ?>
                    </div>
                    <div class="col border">
                        <?= $fiche->getTitle() ?>
                    </div>
                    <div class="col border">
                        <?= $fiche->getCreationDate() ?>
                    </div>
                    <div class="col-2 border">
                        <?= $usersAsso[$fiche->getIdUser()] ?>
                    </div>
                    <div class="col-1 border d-flex justify-content-around align-items-center p-0">

                        <a href="<?=$urlSectionRead?>?id=<?=$fiche->getId()?>" class="btn btn-sm" >
                            <i class="bi bi-eye-fill" width="20px" height="20px" style="color:DodgerBlue;" title="Consulter"></i>
                        </a>

                        <?php if($fiche->getIdUser()==$idUserSession || $isAdmin){?>
                        <a href="<?=($typeAsso[$fiche->getId()] === "Chauve souris") ? $urlEditionBat : $urlEditionColonie?>&id=<?= $fiche->getId() ?>"
                        class="btn btn-sm" style="color:black">
                            <i class="bi bi-pencil-square" width="20px" height="20px" title="Modifier"></i>
                        </a>

                        <a href="<?= $urlDelete ?>?delete=true&id=<?= $fiche->getId() ?>" class="btn btn-sm "
                         style="color: red"
                         onclick="return confirm ('Etes-vous sûr de vouloir supprimer cette fiche ?');">
                            <i class="bi bi-trash3" width="20px" height="20px" title="Supprimer"></i>
                        </a>

                        <?php } else {?>

                        <a href="" class="btn btn-sm disabled border-0" style="color:grey">
                            <i class="bi bi-pencil-square" width="20px" height="20px" title="Modifier"></i>
                        </a>

                         <a href="" class="btn btn-sm disabled border-0" style="color:grey">
                            <i class="bi bi-trash3" width="20px" height="20px" title="Supprimer"></i>
                        </a>

                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <a href="sectionColony" class="btn btn-primary m-1">
        Nouvelle fiche colonie
    </a>
    <a href="sectionBat" class="btn btn-primary m-1">
        Nouvelle fiche individu
    </a>
</div>
