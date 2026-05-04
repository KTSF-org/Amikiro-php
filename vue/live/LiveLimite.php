<?php
/**
 * VUE : LiveLimite
 * Affichée par le contrôleur quand viewerCount >= viewerLimit.
 * Le compteur n'est PAS incrémenté dans ce cas — l'utilisateur n'entre pas en session.
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
                    <h5 class="mb-2">Stream indisponible</h5>
                    <p class="text-muted mb-4">
                        Le nombre maximum de spectateurs simultanés est atteint.<br>
                        Veuillez réessayer dans quelques instants.
                    </p>
                    <a href="<?= $actual_link ?>live" class="btn btn-outline-dark btn-sm px-4">Réessayer</a>
                </div>
            </div>

        </div>
    </div>
</div>
