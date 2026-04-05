<?php
/**
 * VUE : Live
 * Lecteur vidéo HLS via HLS.js (chargé globalement dans header.php).
 * Variables reçues :
 *   $url1 — array : flux HLS principal  (lu par le lecteur <video>)
 *   $url2 — array : flux caméra image   (affiché en balise <img>)
 *
 * HLS.js est chargé via header.php (CDN cdnjs). Deux cas de lecture :
 *   - Safari : lecture native HLS via video.src
 *   - Autres : attachement du flux via l'API Hls
 */
?>
<div class="container-fluid py-4">

    <h2 class="text-center mb-4">Live</h2>

    <!-- Caméra image (flux MJPEG ou snapshot statique) -->
    <img id="image0"
         src="<?= htmlspecialchars($url2['url']) ?>"
         class="img-fluid d-block mx-auto mb-3"
         alt="Caméra gîte">

    <!-- Lecteur HLS — src initialisé en JavaScript ci-dessous -->
    <video id="video"
           controls
           autoplay
           muted
           height="500"
           width="80%"
           class="d-block mx-auto"
           style="padding-bottom: 60px">
    </video>

</div>

<script>
    /**
     * Initialisation du lecteur HLS.
     * $url1['url'] est injecté côté serveur — contient l'URL du flux .m3u8.
     * HLS.js est chargé globalement via header.php.
     */
    const streamUrl = '<?= htmlspecialchars($url1['url']) ?>';

    const video = document.getElementById('video');

    if (video.canPlayType('application/vnd.apple.mpegurl')) {
        // Safari supporte HLS nativement
        video.src = streamUrl;
        video.autoplay = true;
        video.muted = true;
    } else if (Hls.isSupported()) {
        // Autres navigateurs : lecture via HLS.js
        const hls = new Hls();
        hls.loadSource(streamUrl);
        hls.attachMedia(video);
        hls.on(Hls.Events.MEDIA_ATTACHED, function () {
            video.muted = true;
            video.play();
        });
    }
</script>
