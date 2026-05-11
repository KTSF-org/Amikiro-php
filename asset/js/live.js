document.addEventListener('DOMContentLoaded', function () {
    const cfg          = window.liveConfig;
    const video        = document.getElementById('video');
    const sessionTimer = document.getElementById('sessionTimer');
    let hls            = null;
    let leftLive       = false;

    if (Hls.isSupported()) {
        hls = new Hls();
        hls.loadSource(cfg.videoUrl);
        hls.attachMedia(video);
    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
        video.src      = cfg.videoUrl;
        video.autoplay = true;
        video.muted    = true;
    }

    if (cfg.sessionDuration > 0) {
        let remaining = cfg.sessionDuration;
        const timerInterval = setInterval(function () {
            remaining--;
            const m = String(Math.floor(remaining / 60)).padStart(2, '0');
            const s = String(remaining % 60).padStart(2, '0');
            sessionTimer.textContent = m + ':' + s + ' restant';
            if (remaining <= 0) clearInterval(timerInterval);
        }, 1000);
    }

    function stopLive() {
        if (leftLive) return;
        leftLive = true;
        if (hls) { hls.destroy(); hls = null; }
        video.pause();
        video.src = '';
        video.load();
        navigator.sendBeacon(cfg.baseUrl + 'ajax?liveLeave');
        document.querySelector('.live-card').insertAdjacentHTML(
            'afterend',
            '<div class="alert alert-secondary mt-3 text-center">La session de visionnage est terminée.</div>'
        );
    }

    if (cfg.sessionDuration > 0) {
        setTimeout(stopLive, cfg.sessionDuration * 1000);
    }

    window.addEventListener('beforeunload', function () {
        if (!leftLive) navigator.sendBeacon(cfg.baseUrl + 'ajax?liveLeave');
    });

    setInterval(function () {
        const request = new AjaxRequest(cfg.baseUrl + 'ajax?viewerCount', 'GET', {});
        request.send(function (count) {
            document.getElementById('viewerCount').textContent = count;
        });
    }, 10000);
});
