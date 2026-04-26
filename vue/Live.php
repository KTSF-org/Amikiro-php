<?php
/**
 * VUE : Live.php
 */
?>
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Live</h1>
        <span class="badge bg-secondary fs-6">
            <span id="viewerCount"><?= (int)$url1['viewerCount'] ?></span> spectateur(s)
        </span>
    </div>

    <div class="card">
        <div class="card-body p-2">
            <video id="video" class="w-100 rounded" controls autoplay muted></video>
        </div>
    </div>

</div>

<script>
    var video = document.getElementById('video');
    // URL du flux et durée de session injectées depuis la config PHP
    var videoUrl = "<?= $url1['streamUrl'] ?>";
    var sessionDuration = <?= (int)$url1['sessionDuration'] ?>;

    var hls = null;
    var leftLive = false; // true une fois que le compteur a été décrémenté

    // HLS.js gère le format HLS (.m3u8) sur les navigateurs qui ne le supportent pas nativement
    if (Hls.isSupported()) {
        hls = new Hls();
        hls.loadSource(videoUrl);
        hls.attachMedia(video);
        hls.on(Hls.Events.MANIFEST_PARSED, function () {
            console.log('Manifest chargé et prêt à jouer');
        });
    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
        // Safari supporte HLS nativement, pas besoin de HLS.js
        video.src = videoUrl;
        video.autoplay = true;
        video.muted = true;
    }

    // Arrête le stream, libère la ressource et décrémente le compteur côté serveur
    function stopLive() {
        if (leftLive) return; // déjà décrémenté, évite le double appel
        leftLive = true;
        if (hls) {
            hls.destroy();
            hls = null;
        }
        video.pause();
        video.src = '';
        video.load();
        navigator.sendBeacon('<?= $actual_link ?>ajax?liveLeave');
        document.querySelector('.card').insertAdjacentHTML(
            'afterend',
            '<div class="alert alert-secondary mt-3 text-center">La session de visionnage est terminée.</div>'
        );
    }

    // Coupe automatiquement la session après la durée configurée (en secondes)
    if (sessionDuration > 0) {
        setTimeout(stopLive, sessionDuration * 1000);
    }

    // sendBeacon garantit l'envoi même si la page est en train de se fermer
    // (fetch/XHR seraient annulés par le navigateur au beforeunload)
    // leftLive évite un double décrémente si stopLive() a déjà été appelé
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
