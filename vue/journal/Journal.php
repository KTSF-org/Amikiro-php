<?php
/**
 * VUE : Journal.php
 */
?>
<script src="asset/js/tableau.js"></script>
<div class="container py-3">

    <h1 class="mb-3"><?= ($mesFiches) ? 'Mes fiches' : 'Journal' ?></h1>

    <div class="table-responsive">
        <table id="listSection" class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Titre</th>
                    <th>Dernière modification</th>
                    <th>Auteur</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listFiches as $fiche): ?>
                    <tr>
                        <td><?= $fiche->getId() ?></td>
                        <td>
                            <?php if ($typeAsso[$fiche->getId()] === 'Colonie'): ?>
                                <span class="badge rounded-pill" style="background-color: var(--amk-navy);">Colonie</span>
                            <?php else: ?>
                                <span class="badge rounded-pill" style="background-color: var(--amk-green);">Individu</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($fiche->getTitle()) ?></td>
                        <td class="text-muted small"><?= $fiche->getModifDate() ?></td>
                        <td><?= htmlspecialchars($usersAsso[$fiche->getIdUser()] ?? '—') ?></td>
                        <td>
                            <!-- Consulter — accessible à tous -->
                            <a href="<?= $urlSectionRead ?>?id=<?= $fiche->getId() ?>"
                               class="btn btn-sm btn-link p-1" title="Consulter">
                                <i class="bi bi-eye-fill icon-view fs-5"></i>
                            </a>

                            <?php
                            // Modifier/Supprimer : réservé aux naturalistes qui sont auteurs, et aux admins.
                            $canWrite = ($fiche->getIdUser() == $idUserSession && $userRole >= ROLE_NATURALISTE) || $isAdmin;
                            ?>
                            <?php if ($canWrite): ?>
                                <!-- Modifier -->
                                <a href="<?= ($typeAsso[$fiche->getId()] === 'Chauve souris') ? $urlEditionBat : $urlEditionColonie ?>&id=<?= $fiche->getId() ?>"
                                   class="btn btn-sm btn-link p-1" title="Modifier">
                                    <i class="bi bi-pencil-square icon-edit fs-5"></i>
                                </a>
                                <!-- Supprimer -->
                                <a href="<?= $urlDelete ?>?delete=true&id=<?= $fiche->getId() ?>"
                                   class="btn btn-sm btn-link p-1" title="Supprimer"
                                   onclick="return confirm('Supprimer cette fiche ?');">
                                    <i class="bi bi-trash3 icon-delete fs-5"></i>
                                </a>
                            <?php else: ?>
                                <!-- Actions désactivées — lecture seule pour les adhérents -->
                                <span class="btn btn-sm btn-link p-1 icon-disabled" title="Lecture seule">
                                    <i class="bi bi-pencil-square fs-5"></i>
                                </span>
                                <span class="btn btn-sm btn-link p-1 icon-disabled" title="Lecture seule">
                                    <i class="bi bi-trash3 fs-5"></i>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($userRole >= ROLE_NATURALISTE): ?>
    <div class="mt-3">
        <a href="sectionColony" class="btn me-2" style="background-color: #3C5060; color: white">
            <i class="bi bi-plus-lg me-1"></i>Nouvelle fiche colonie
        </a>
        <a href="sectionBat" class="btn" style="background-color: #3C5060; color: white">
            <i class="bi bi-plus-lg me-1"></i>Nouvelle fiche individu
        </a>
    </div>
    <?php endif; ?>

</div>
