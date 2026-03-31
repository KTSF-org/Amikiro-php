<?php
/**
 * VUE : Live.php
 */

?>
<div class="container-fluid">
    <H2 style="text-align: center">Live</H2>

    <img id="image0" src=<?= $url2["url"] ?> class="img-responsive" alt=""
    title="Click here to enter the camera located in Mexico, region Jalisco">
<!--    si url de ce type il faut faire un proxy pour cacher l'url-->

    <video id="video" controls height="500" src="" autoplay muted width="80%" style="padding-bottom: 60px"></video>

</div>

<script>
    const streamUrl = '<?= $url1["url"] ?>';
    /** 'https://demo.unified-streaming.com/k8s/features/stable/video/tears-of-steel/tears-of-steel.ism/.m3u8'; */

    const video = document.getElementById('video');

    if (video.canPlayType('application/vnd.apple.mpegurl')) {
        // Safari : lecture native
        video.src = streamUrl;
        video.autoplay = true;
        video.muted = true;
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