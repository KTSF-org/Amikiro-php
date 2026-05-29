<?php
/**
 * VUE : Live
 * Variables reçues :
 * $url1 — array : ligne Config (streamUrl, sessionDuration, viewerCount, viewerLimit, streamOnline)
 *
 * Le compteur viewerCount a déjà été incrémenté par le contrôleur avant d'arriver ici.
 * Le décrément se fait côté client via sendBeacon (beforeunload ou fin de sessionDuration).
 */

// On récupère le statut du flux (vrai par défaut si non défini)
$isOnline = isset($url1['streamOnline']) ? (bool)$url1['streamOnline'] : true;
?>
<div class="live-page">

    <div class="live-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-0 h3">Live</h1>
            <small class="text-muted"><?= htmlspecialchars(APP_NAME) ?></small>
        </div>
        <span class="viewer-badge">
            <i class="bi bi-people-fill viewer-icon"></i>
            <span id="viewerCount"><?= (int)$url1['viewerCount'] ?></span>
            <span class="viewer-count-label">spectateur(s)</span>
        </span>
    </div>

    <div class="card live-card">
        <div class="card-header bg-dark text-white py-2 d-flex justify-content-between align-items-center">
            <span class="fw-semibold small d-flex align-items-center gap-2">
                <span class="<?= $isOnline ? 'live-dot' : 'badge bg-danger rounded-circle p-1 d-inline-block' ?>" style="<?= !$isOnline ? 'width:10px;height:10px;' : '' ?>"></span>
                <?= $isOnline ? 'Flux en direct' : 'Flux indisponible' ?>
            </span>
            <span class="text-white-50 small" id="sessionTimer"></span>
        </div>
        <div class="card-body p-0 position-relative">
            <?php if ($isOnline): ?>
                <video id="video" class="w-100 h-100" style="background: #000;" controls autoplay muted></video>
            <?php else: ?>
                <div class="d-flex flex-column align-items-center justify-content-center text-center p-5 text-muted" style="min-height: 350px; background: #f8f9fa;">
                    <i class="bi bi-camera-video-off text-danger mb-3" style="font-size: 3rem;"></i>
                    <h5 class="fw-bold text-dark">La webcam est hors ligne</h5>
                    <p class="small text-muted max-width-300">
                        Le flux vidéo est actuellement inaccessible. <br>
                        L'appareil est peut-être éteint ou en cours de maintenance.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
window.liveConfig = {
    // Si le flux est HS, on passe une chaîne vide ou l'URL pour que le JS puisse gérer si besoin
    videoUrl:        "<?= $isOnline ? htmlspecialchars($url1['streamUrl'], ENT_QUOTES) : '' ?>",
    sessionDuration: <?= (int)$remaining ?>,
    baseUrl:         "<?= $actual_link ?>"
};
</script>