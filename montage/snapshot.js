var choice = "";
var file = false;
var filter = null;
pos_x = pos_y = 0;
var selected = null,
x_pos = 0, y_pos = 0,
x_elem = 0, y_elem = 0;

var video = document.getElementById('camera-stream');
video.addEventListener('canplay', hidden_div);

//Move the filter
function start() {
    selected = filter;
    x_elem = x_pos - selected.offsetLeft;
    y_elem = y_pos - selected.offsetTop;
}

function move(filter) {
    x_pos = document.all ? window.event.clientX : filter.pageX;
    y_pos = document.all ? window.event.clientY : filter.pageY;
    if (selected !== null) {
        selected.style.left = (x_pos - x_elem) + 'px';
        selected.style.top = (y_pos - y_elem) + 'px';
        pos_x = x_pos - x_elem;
        pos_y = y_pos - y_elem;
    }
}

function end() {
    selected = null;
}

function hidden_div() {
    camera = document.getElementById('camera');

    camera.setAttribute('width', video.videoWidth);
    camera.setAttribute('height', video.videoHeight);
}

//Choose the filter
document.getElementById('choice1').onclick = function() {
    document.getElementById('snapshot').style.display = "block";
    camera = document.getElementById('camera');
    video = document.getElementById('camera-stream');
    if (filter)
        filter.src = 'images/cat.png';
    else {
        filter = document.createElement('img');
        filter.setAttribute('id', 'filter_display');
        filter.addEventListener('mousedown', start);
        filter.addEventListener('mousemove', move);
        filter.addEventListener('mouseup', end);
        filter.src = 'images/cat.png';
        camera.insertBefore(filter, video);
    }
    choice = 'Happy';
}

document.getElementById('choice2').onclick = function() {
    document.getElementById('snapshot').style.display = "block";
    camera = document.getElementById('camera');
    video = document.getElementById('camera-stream');
    if (filter)
        filter.src = 'images/angerfist.png';
    else {
        filter = document.createElement('img');
        filter.setAttribute('id', 'filter_display');
        filter.addEventListener('mousedown', start);
        filter.addEventListener('mousemove', move);
        filter.addEventListener('mouseup', end);
        filter.src = 'images/angerfist.png';
        camera.insertBefore(filter, video);
    }
    choice = 'Angerfist';
}

document.getElementById('choice3').onclick = function() {
    document.getElementById('snapshot').style.display = "block";
    camera = document.getElementById('camera');
    video = document.getElementById('camera-stream');
    if (filter)
        filter.src = 'images/portal.png';
    else {
        filter = document.createElement('img');
        filter.setAttribute('id', 'filter_display');
        filter.addEventListener('mousedown', start);
        filter.addEventListener('mousemove', move);
        filter.addEventListener('mouseup', end);
        filter.src = 'images/portal.png';
        camera.insertBefore(filter, video);
    }
    choice = "Portal"; 
}

//Get file
function onSelectedFile() {
    var img = document.getElementById('file-stream');
    file = event.target.files[0];
    // console.log(file);
    file_type = file.type;
    if (file_type == 'image/png') {
        img.src = URL.createObjectURL(file);
        document.getElementById('file-stream').style.display = "block";
        document.getElementById('camera-stream').style.display = "none";
        file = true;
    }
    else {
        alert('Sorry, the extension of your file does not work, are accepted : .png');
        document.location.href = 'montage.php';
    }
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
    var support;

    if (!file) {
        support = document.getElementById('camera-stream');
        // console.log(support.width, support.height);
    } else {
        support = document.getElementById('file-stream');
        // console.log(support.width, support.height);
    }
    var canvas = document.getElementById('canvas');
    var ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.drawImage(support, canvas.width/2 - support.width/2,
        canvas.height/2 - support.height/2, support.width, support.height);

    var image = new Image();
    image.src = canvas.toDataURL();

    var req = new XMLHttpRequest();
    req.open("POST", "test.php");
    req.onreadystatechange = function() {
        if (req.status == 200 && req.readyState == XMLHttpRequest.DONE) {
            var ret = req.responseText;
            if (ret == 'Fail') {
                alert('You need to choose your filter first !');
                document.location.href = 'montage.php';
            }
            var img = document.getElementById('image');
            img.setAttribute('src', ret);
            document.getElementById('image').style.display = "block";
            document.getElementById('validate').style.display = "block";
        }
    };
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send("choice="+choice+"&image="+image.src+"&x_pos="+pos_x+"&y_pos="+pos_y);
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
