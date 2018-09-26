<link rel="stylesheet" type="text/css" href="./gallery.css">

<?php
    $id = $_SESSION['id_user'];
    $request = "SELECT link FROM `pictures` WHERE USER_ID='$id' AND validate='1' ORDER BY id DESC";
    $images = $pdo->query($request);
    foreach ($images as $image) {
        $officiel = str_replace(' ', '+', $image[0]);
        echo "<img class='officiel' src='$officiel'/>";
    }
?>