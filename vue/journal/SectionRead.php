<?php

/**
 * VUE : SectionRead.php
 */

?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Fil d'ariane / Bouton Retour -->
            <nav aria-label="breadcrumb" class="mb-4">
                <a href="<?=$urlRetour?>" class="btn btn-link text-decoration-none p-0 text-secondary">
                    <i class="bi bi-chevron-left"></i> Retour au journal
                </a>
            </nav>

            <div class="card shadow-sm border-0">
                <!-- Header avec dégradé léger -->
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h1 class="h3 fw-bold text-dark mb-1"><?= htmlspecialchars($sectionTitle) ?></h1>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-calendar3 me-1"></i> Créée le <?= $creationDate ?>
                            </p>
                        </div>
                        <!-- Badge dynamique : Catégorie ou Chauve-souris -->
                        <span class="badge rounded-pill bg-primary px-3 py-2">
                            <?= !empty($nameCategory) ? htmlspecialchars($nameCategory) : htmlspecialchars($nameBat) ?>
                        </span>
                    </div>
                </div>

                <!-- Corps de la fiche -->
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label text-uppercase fw-bold text-muted small mb-3">Observations & Contenu</label>
                        <div class="bg-light p-4 rounded-3 border-start border-4 border-amk-green">
                            <p class="mb-0 text-dark" style="white-space: pre-wrap; line-height: 1.6;">
                                <?= nl2br(htmlspecialchars($sectionContent)) ?>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
