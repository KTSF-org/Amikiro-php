<?php

/**
 * VUE : Accueil.php
 */

?>
<div class="container-fluid">
    <H2 style="text-align: center">Live</H2>
    <video id="video" controls height="500" src="asset\media\VID-20250530-WA0000.mp4" autoplay width="80%"></video>
</div>

<script>
    const streamUrl = 'https://demo.unified-streaming.com/k8s/features/stable/video/tears-of-steel/tears-of-steel.ism/.m3u8';

    const video = document.getElementById('video');

    if (video.canPlayType('application/vnd.apple.mpegurl')) {
        // Safari : lecture native
        video.src = streamUrl;
    } else if (Hls.isSupported()) {
        // Autres navigateurs : HLS.js
        const hls = new Hls();
        hls.loadSource(streamUrl);
        hls.attachMedia(video);
        hls.on(Hls.Events.MEDIA_ATTACHED, function(){
            video.muted =true;
            video.play();
        })
    }
</script>