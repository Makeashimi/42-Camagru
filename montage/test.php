<?php 
session_start();
require_once("../config/pdo.php");

if (!empty($_POST['choice']) && !empty($_POST['image'])) {
    $id = $_SESSION['id_user'];
    $choice = $_POST['choice'];
    $image = $_POST['image'];
    $image = str_replace(' ', '+', $image);
    $link_explode = explode(',', $image);
    $decode = base64_decode($link_explode[1]);
    
    $dest = imagecreatefromstring($decode);
    if ($choice == 'Happy') {
        $src = imagecreatefrompng('images/cat.png');
        $dest_x = $_POST['x_pos'];
        $dest_y = $_POST['y_pos'];
        $src_w = 200;
        $src_h = 109;
    }
    else if ($choice == 'Angerfist') {
        $src = imagecreatefrompng('images/angerfist.png');
        $dest_x = $_POST['x_pos'];
        $dest_y = $_POST['y_pos'];
        $src_w = 500;
        $src_h = 500;
    }
    else if ($choice == 'Portal') {
        $src = imagecreatefrompng('images/portal.png');
        $dest_x = $_POST['x_pos'];
        $dest_y = $_POST['y_pos'];
        $src_w = 150;
        $src_h = 150;
    }
    imagecopy($dest, $src, $dest_x, $dest_y, 0, 0, $src_w, $src_h);
    imagepng($dest, 'images/image.png');

    $data = file_get_contents('images/image.png');
    $image = $link_explode[0].','.base64_encode($data);
    $request = "INSERT INTO `pictures` (USER_ID, CHOICE, LINK, VALIDATE) VALUES ('$id', '$choice', '$image', 0)";
    $pdo->exec($request);
    echo $image;
}
else
    echo "Fail";

if (isset($_POST['validate'])) {
    $id = $_SESSION['id_user'];
    $request = "UPDATE `pictures` SET validate='1' WHERE user_id='$id' ORDER BY id DESC LIMIT 1";
    $pdo->exec($request);
}

?>