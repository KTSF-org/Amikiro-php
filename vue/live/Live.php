<?php
/**
 * VUE : Live
 * Variables reçues :
 *   $url1 — array : ligne Config (streamUrl, sessionDuration, viewerCount, viewerLimit)
 *
 * Le compteur viewerCount a déjà été incrémenté par le contrôleur avant d'arriver ici.
 * Le décrément se fait côté client via sendBeacon (beforeunload ou fin de sessionDuration).
 */
?>
<div class="live-page">

    <!-- En-tête : titre + compteur de viewers -->
    <div class="live-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-0 h3">Live</h1>
            <small class="text-muted"><?= htmlspecialchars(APP_NAME) ?></small>
        </div>
        <!-- Compteur mis à jour toutes les 10 s via AJAX -->
        <span class="badge bg-dark fs-6 fw-normal px-3 py-2">
            <span id="viewerCount"><?= (int)$url1['viewerCount'] ?></span>
            <span class="text-white-50 small ms-1">spectateur(s)</span>
        </span>
    </div>

    <!-- Card vidéo — flex-grow remplit la hauteur restante -->
    <div class="card live-card">
        <div class="card-header bg-dark text-white py-2 d-flex justify-content-between align-items-center">
            <span class="fw-semibold small">Flux en direct</span>
            <!-- Décompte de session injecté par JS -->
            <span class="text-white-50 small" id="sessionTimer"></span>
        </div>
        <div class="card-body">
            <video id="video" controls autoplay muted></video>
        </div>
    </div>

</div>

<script>
window.liveConfig = {
    videoUrl:        "<?= htmlspecialchars($url1['streamUrl'], ENT_QUOTES) ?>",
    sessionDuration: <?= (int)$remaining ?>,
    baseUrl:         "<?= $actual_link ?>"
};
</script>
