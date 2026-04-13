<?php
/**
 * VUE : Live.php
 */

?>
<div class="container-fluid">
    <H2 style="text-align: center">Live</H2>

    <video id="video" controls height="500" src="" autoplay muted width="80%" style="padding-bottom: 60px"></video>
</div>

    
    <script>
        var video = document.getElementById('video');
        var videoUrl = "<?= $url2['streamUrl'] ?>";

        if (Hls.isSupported()) {
            var hls = new Hls();
            hls.loadSource(videoUrl);
            hls.attachMedia(video);
            hls.on(Hls.Events.MANIFEST_PARSED, function () {
                console.log('Manifest chargé et prêt à jouer');
            });
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            video.src = videoUrl;
            video.autoplay = true;
            video.muted = true;
        }
    </script>
