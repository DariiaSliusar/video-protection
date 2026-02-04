<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Secure Video Stream</title>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <style>
        body { background: #1a1a1a; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; font-family: sans-serif; }
        video { width: 80%; max-width: 900px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); background: #000; }
        #error-msg { color: white; display: none; position: absolute; top: 20px; background: rgba(255,0,0,0.5); padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>

<div id="error-msg">Error loading video. Please refresh the page.</div>

<video id="video" controls controlsList="nodownload" crossorigin="use-credentials"></video>

<script>
    const video = document.getElementById('video');
    const errorMsg = document.getElementById('error-msg');
    const videoSrc = "{{ route('video.playlist') }}";

    if (Hls.isSupported()) {
        const hls = new Hls({
            xhrSetup: function (xhr, url) {
                xhr.withCredentials = true;
            }
        });

        hls.loadSource(videoSrc);
        hls.attachMedia(video);

        hls.on(Hls.Events.ERROR, function (event, data) {
            if (data.fatal) {
                console.error("Fatal error:", data.details);
                errorMsg.style.display = 'block';
            }
        });
    }
    else if (video.canPlayType('application/vnd.apple.mpegurl')) {
        video.src = videoSrc;
    }
</script>
</body>
</html>
