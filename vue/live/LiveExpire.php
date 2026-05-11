<?php
/**
 * VUE : LiveExpire
 * Affichée quand la durée de session de visionnage est écoulée.
 * Le viewer n'est pas re-comptabilisé — la session reste fermée.
 */
?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="mb-4">
                <h1 class="mb-0 h3">Live</h1>
                <small class="text-muted"><?= htmlspecialchars(APP_NAME) ?></small>
            </div>

            <div class="card">
                <div class="card-header bg-dark text-white py-2">
                    <span class="fw-semibold small">Flux en direct</span>
                </div>
                <div class="card-body text-center py-5">
                    <p class="fs-1 mb-3">🦇</p>
                    <h5 class="mb-2">Session terminée</h5>
                    <p class="text-muted mb-0">
                        Votre durée de visionnage est écoulée.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
