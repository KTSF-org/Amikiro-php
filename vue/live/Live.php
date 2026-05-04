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
<style>
/*
 * Layout plein écran sans scroll :
 * 56px = hauteur de la navbar Bootstrap sticky-top (3.5rem).
 * dvh (dynamic viewport height) gère le chrome mobile ; vh en fallback.
 */
.live-page {
    height: calc(100vh - 56px);
    height: calc(100dvh - 56px);
    display: flex;
    flex-direction: column;
    padding: 1rem;
    overflow: hidden;
    box-sizing: border-box;
}
.live-header   { flex-shrink: 0; margin-bottom: 0.75rem; }
/* La card prend tout l'espace restant après le header */
.live-card     { flex: 1; display: flex; flex-direction: column; min-height: 0; }
.live-card .card-body { flex: 1; min-height: 0; display: flex; flex-direction: column; padding: 0.5rem; }
/* min-height: 0 est nécessaire — sans ça, un enfant flex ne rétrécit pas en dessous de sa taille intrinsèque */
.live-card video {
    flex: 1;
    min-height: 0;
    width: 100%;
    object-fit: contain;
    background: #000;
    border-radius: 0.375rem;
}
</style>

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
    const video           = document.getElementById('video');
    const sessionTimer    = document.getElementById('sessionTimer');
    // URL du flux et durée de session depuis la config (table Config, ligne unique id=1)
    const videoUrl        = "<?= htmlspecialchars($url1['streamUrl'], ENT_QUOTES) ?>";
    const sessionDuration = <?= (int)$url1['sessionDuration'] ?>;

    let hls      = null;
    let leftLive = false; // garde-fou : évite de décrémenter le compteur deux fois

    // HLS.js gère le format HLS (.m3u8) sur les navigateurs qui ne le supportent pas nativement
    if (Hls.isSupported()) {
        hls = new Hls();
        hls.loadSource(videoUrl);
        hls.attachMedia(video);
    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
        // Safari supporte HLS nativement, HLS.js non nécessaire
        video.src      = videoUrl;
        video.autoplay = true;
        video.muted    = true;
    }

    // Décompte affiché dans le header de la card
    if (sessionDuration > 0) {
        let remaining = sessionDuration;
        const timerInterval = setInterval(function () {
            remaining--;
            const m = String(Math.floor(remaining / 60)).padStart(2, '0');
            const s = String(remaining % 60).padStart(2, '0');
            sessionTimer.textContent = m + ':' + s + ' restant';
            if (remaining <= 0) clearInterval(timerInterval);
        }, 1000);
    }

    // Arrête le stream, libère la ressource HLS et décrémente le compteur côté serveur
    function stopLive() {
        if (leftLive) return;
        leftLive = true;
        if (hls) { hls.destroy(); hls = null; }
        video.pause();
        video.src = '';
        video.load();
        // sendBeacon est utilisé car fetch/XHR sont annulés par le navigateur au beforeunload
        navigator.sendBeacon('<?= $actual_link ?>ajax?liveLeave');
        document.querySelector('.live-card').insertAdjacentHTML(
            'afterend',
            '<div class="alert alert-secondary mt-3 text-center">La session de visionnage est terminée.</div>'
        );
    }

    // Coupe automatiquement la session après la durée configurée
    if (sessionDuration > 0) {
        setTimeout(stopLive, sessionDuration * 1000);
    }

    // leftLive évite le double décrément si stopLive() a déjà été appelé par le timeout
    window.addEventListener('beforeunload', function () {
        if (!leftLive) navigator.sendBeacon('<?= $actual_link ?>ajax?liveLeave');
    });

    // Rafraîchit le compteur de viewers toutes les 10 secondes
    setInterval(function () {
        const request = new AjaxRequest('<?= $actual_link ?>ajax?viewerCount', 'GET', {});
        request.send(function (count) {
            document.getElementById('viewerCount').textContent = count;
        });
    }, 10000);
</script>
