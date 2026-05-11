<?php

/**
 * VUE : Journal.php
 */

?>
<script src="asset/js/tableau.js"></script>
<div class="container">

    <h1 class="m-2"><?= ($mesFiches) ? 'Mes fiches' : 'Journal' ?></h1>

    <div class="col m-2">
        <table id="listSection">
            <thead>
                <th>
                    #
                </th>
                <th>
                    Type
                </th>
                <th>
                    Titre
                </th>
                <th>
                    Dernière modification
                </th>
                <th>
                    Auteur
                </th>
                <th>
                    Actions
                </th>
            </thead>
            <tbody>
                <?php foreach ($listFiches as $fiche) { ?>
                    <tr>
                        <td>
                            <?= $fiche->getId() ?>
                        </td>
                        <td>
                            <?= $typeAsso[$fiche->getId()] ?>
                        </td>
                        <td>
                            <?= $fiche->getTitle() ?>
                        </td>
                        <td>
                            <?= $fiche->getModifDate() ?>
                        </td>
                        <td>
                            <?= $usersAsso[$fiche->getIdUser()] ?>
                        </td>
                        <td>

                            <a href="<?= $urlSectionRead ?>?id=<?= $fiche->getId() ?>" class="btn btn-sm">
                                <i class="bi bi-eye-fill" width="20px" height="20px" style="color:DodgerBlue;"
                                    title="Consulter"></i>
                            </a>

                            <?php if ($fiche->getIdUser() == $idUserSession || $isAdmin) { ?>
                                <a href="<?= ($typeAsso[$fiche->getId()] === "Chauve souris") ? $urlEditionBat : $urlEditionColonie ?>&id=<?= $fiche->getId() ?>"
                                    class="btn btn-sm" style="color:black">
                                    <i class="bi bi-pencil-square" width="20px" height="20px" title="Modifier"></i>
                                </a>

                                <a href="<?= $urlDelete ?>?delete=true&id=<?= $fiche->getId() ?>" class="btn btn-sm "
                                    style="color: red"
                                    onclick="return confirm ('Etes-vous sûr de vouloir supprimer cette fiche ?');">
                                    <i class="bi bi-trash3" width="20px" height="20px" title="Supprimer"></i>
                                </a>

                            <?php } else { ?>

                                <a href="" class="btn btn-sm disabled border-0" style="color:grey">
                                    <i class="bi bi-pencil-square" width="20px" height="20px" title="Modifier"></i>
                                </a>

                                <a href="" class="btn btn-sm disabled border-0" style="color:grey">
                                    <i class="bi bi-trash3" width="20px" height="20px" title="Supprimer"></i>
                                </a>

                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <a href="sectionColony" class="btn btn-primary m-1">
        Nouvelle fiche colonie
    </a>
    <a href="sectionBat" class="btn btn-primary m-1">
        Nouvelle fiche individu
    </a>
</div>
