var choice;
var file = false;
var img;

//Choose the filter
document.getElementById('choice1').onclick = function() {
    document.getElementById('snapshot').style.display = "block";
    choice = 'Happy';
}

document.getElementById('choice2').onclick = function() {
    document.getElementById('snapshot').style.display = "block";
    choice = 'Normandie';
}

document.getElementById('choice3').onclick = function() {
    document.getElementById('snapshot').style.display = "block";
    choice = "Portal"; 
}

//Get file
function onSelectedFile() {
    img = document.getElementById('file-stream');
    img.src = URL.createObjectURL(event.target.files[0]);
    document.getElementById('file-stream').style.display = "block";
    document.getElementById('camera-stream').style.display = "none";
    file = true;
}

//Get webcam
window.onload = function() {
    navigator.getUserMedia = (navigator.getUserMedia ||
                navigator.webkitGetUserMedia ||
                navigator.mozGetUserMedia ||
                navigator.msGetUserMedia);
}
if (navigator.getUserMedia) {
    navigator.getUserMedia({video: true}, function(localMediaStream) {
        var vid = document.getElementById('camera-stream');
        vid.srcObject = localMediaStream;
        vid.play();
    },
    function(err) {
            console.log('The following error occurred when trying to use getUserMedia: ' + err);
        }
    );
} else {
    alert('Sorry, your browser does not support getUserMedia..');
}

//Make the snapshot, send it to back and get the new image
document.getElementById('snapshot').onclick = function() {
    var req = new XMLHttpRequest();

    if (!file) {
        var video = document.querySelector('video');
        var canvas = document.getElementById('canvas');
        var ctx = canvas.getContext('2d');
        ctx.drawImage(video,0,0,500,376);

        var image = new Image();
        image.src = canvas.toDataURL();
        
        req.open("POST", "test.php");
        req.onreadystatechange = function() {
            if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
                var ret = req.responseText;
                var img = document.getElementById('image');
                img.setAttribute('src', ret);
                document.getElementById('image').style.display = "block";
                document.getElementById('validate').style.display = "block";
            }
        };
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send("choice="+choice+"&image="+image.src);
    }
    else {
        req.open("POST", "test.php");
        req.onreadystatechange = function() {
            if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
                var ret = req.responseText;
                var img = document.getElementById('file_img');
                img.src = ret;
                document.getElementById('file_img').style.display = "block";
                document.getElementById('validate').style.display = "block";
            }
        };
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send("choice="+choice+"&file="+img.src);
    }
}

//Send the finale image
document.getElementById('validate').onclick = function() {
    var req = new XMLHttpRequest();
    req.open("POST", "test.php");
    req.onreadystatechange = function() {
        if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
            document.location.href = 'montage.php';
        }
    };
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send("validate="+"done");
}
