<link rel="stylesheet" type="text/css" href="./webcam.css">

<input type="file" id='file' onchange="onSelectedFile()" accept="image/png, image/jpeg"/>
<video id="camera-stream" width="500" autoplay></video>
<img id="file-stream" src="" size="auto">
<?php
        $id = $_SESSION['id_user'];
        $request = "SELECT COUNT(*) FROM `pictures` WHERE USER_ID='$id'";
        $nb_images = $pdo->query($request)->fetch()[0];
        if ($nb_images < 21) {
            echo "<input type='button' id='snapshot' value='Say cheese !'/>";
        }
        else {
            echo "<input type='button' id='snapshot' value='Say cheese !' disabled/>Sorry, you can't take over 21 pictures ! Delete them to take more d:";
        }
?>
<canvas id='canvas' width='500' height='376'></canvas>
<input class="validate_button" type='button' id='validate' value='Love this one !'/>
<script type="text/javascript" src="snapshot.js"></script>

<?php
    if (isset($_POST['index'])) {
        $text = $_POST['index'];
        $user = $_SESSION['user'];
        $request = "SELECT id FROM `users` WHERE name='$user'";
        $id = $pdo->query($request)->fetch()[0];
        $req = "INSERT INTO `pictures` (LINK, USER_ID) VALUES ('$text', $id)";
        $pdo->exec($req);
    }

    /*<input type=hidden id=variableAPasser value=<?php eco $variableAPasser; ?>/>
    //JavaScript
    var variableRecuperee = document.getElementById(variableAPasser).value;*/

?>
