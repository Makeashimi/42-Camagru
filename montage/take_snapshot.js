var image;

window.onload = function() {
    navigator.getUserMedia = (navigator.getUserMedia ||
                navigator.webkitGetUserMedia ||
                navigator.mozGetUserMedia || 
                navigator.msGetUserMedia);
}
if (navigator.getUserMedia) {
    navigator.getUserMedia({video: true}, function(localMediaStream) {
        var vid = document.getElementById('camera-stream');
        vid.src = window.URL.createObjectURL(localMediaStream);
    },
    function(err) {
            console.log('The following error occurred when trying to use getUserMedia: ' + err);
        }
    );
} else {
    alert('Sorry, your browser does not support getUserMedia..');
}

document.getElementById('snapshot').onclick = function() {
    var video = document.querySelector('video');
    var canvas = document.getElementById('canvas');
    var ctx = canvas.getContext('2d');
    ctx.drawImage(video,0,0,500,376);

    image = new Image();
    image.src = canvas.toDataURL();
    document.getElementById('validate').style.display = "block";
}

document.getElementById('validate').onclick = function() {
    var req = new XMLHttpRequest();
    req.open("POST", "montage.php");
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send("index="+image.src);
    document.location.href = 'montage.php';
}
