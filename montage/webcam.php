<div class="container_video">
    <div class="video">
        <video id="camera-stream" width="500" autoplay></video>
        <input type='button' id='snapshot' value="Say cheese !"/>
        <canvas id='canvas' width='500' height='376'></canvas>
        <script type="text/javascript">
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

                var image = new Image();
                image.src = canvas.toDataURL();
                // console.log(image.src);
                
                var req = new XMLHttpRequest();
                req.open("POST", "montage.php");
                req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                req.send("index="+image.src);
            }
        </script>
    </div>
</div>

<?php
    if (isset($_POST['index'])) {
        $text = $_POST['index'];
        $user = $_SESSION['user'];
        $request = "SELECT id FROM `users` WHERE name='$user'";
        $id = $pdo->query($request)->fetch()[0];
        $req = "INSERT INTO `pictures` (LINK, USER_ID) VALUES ('$text', $id)";
        $pdo->exec($req);
    }

    $request = "SELECT link FROM `pictures` ORDER BY id DESC";
    $images = $pdo->query($request);
    foreach ($images as $image) {
        $officiel = str_replace(' ', '+', $image[0]);
        echo "<img src='$officiel'/> <br/>";
    }
    /*<input type=hidden id=variableAPasser value=<?php eco $variableAPasser; ?>/>
    //JavaScript
    var variableRecuperee = document.getElementById(variableAPasser).value;*/
?>

