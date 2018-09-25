var image;
var choice;

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
    var img = document.getElementById('file-stream');
    img.src = URL.createObjectURL(event.target.files[0]);
    document.getElementById('file-stream').style.display = "block";
    document.getElementById('camera-stream').style.display = "none";
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

// function drawCanvas(req) {
//     if (req.readyState == XMLHttpRequest.DONE && req.status == 200) {
//         var video = document.querySelector('video');
//         var canvas = document.getElementById('canvas');
//         var ctx = canvas.getContext('2d');
//         ctx.drawImage(video,0,0,500,376);

//         image = new Image();
//         image.src = canvas.toDataURL();
//         document.getElementById('validate').style.display = "block";
//     }
// }

//Get the snapshot and send it to back
document.getElementById('snapshot').onclick = function() {
    var video = document.querySelector('video');
    var canvas = document.getElementById('canvas');
    var ctx = canvas.getContext('2d');
    ctx.drawImage(video,0,0,500,376);
    //document.getElementById('canvas').style.display = "block";
    image = new Image();
    image.src = canvas.toDataURL();
    // document.getElementById('validate').style.display = "block";

    var req = new XMLHttpRequest();
    req.open("POST", "test.php");
    req.onreadystatechange = function() {
        if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
            //AFFICHER LA BONNE IMAGE
            var ret = req.responseText.split(',');
            var img = document.getElementById('image');
            img.src = ret[1].replace(' ', '+');
            document.getElementById('image').style.display = "block";
        }
    };
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send("choice="+choice+"&image="+image.src);
}

//Validate the finale image
// document.getElementById('validate').onclick = function() {
//     var req = new XMLHttpRequest();
//     req.open("POST", "montage.php");
//     req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//     req.send("index="+image.src);
//     document.location.href = 'montage.php';
// }
