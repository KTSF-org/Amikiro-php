<?php
/**
 * VUE : Live.php
 */

?>
<div class="container-fluid">
    <H2 style="text-align: center">Live</H2>

    <div class="text-center"><video id="video" controls height="500" src="" autoplay muted width="80%"
            style="padding-bottom: 60px"></video></div>
    <div class="text-center">
        <h1 style="text-align;"><?= $url1['viewerCount'] . " sViewers" ?></h1>
    </div>
</div>


<script>
    var video = document.getElementById('video');
    var videoUrl = "<?= $url1['streamUrl'] ?>";
    var sessionDuration = <?= (int) $url1['sessionDuration'] ?>;

    var hls = null;

    if (Hls.isSupported()) {
        hls = new Hls();
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

    function stopLive() {
        if (hls) {
            hls.destroy();
            hls = null;
        }
        video.pause();
        video.src = '';
        video.load();
        navigator.sendBeacon('<?= $actual_link ?>ajax?liveLeave');
        video.parentElement.insertAdjacentHTML(
            'beforeend',
            '<p class="text-center text-muted mt-3">La session de visionnage est terminée.</p>'
        );
    }

    if (sessionDuration > 0) {
        setTimeout(stopLive, sessionDuration * 1000);
    }

    window.addEventListener('beforeunload', function () {
        navigator.sendBeacon('<?= $actual_link ?>ajax?liveLeave');
    });
</script>