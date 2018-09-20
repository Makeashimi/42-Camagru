<link rel="stylesheet" type="text/css" href="./gallery.css">

<?php
    $name = $_SESSION['user'];
    $request = "SELECT id FROM `users` WHERE name='$name'";
    $id = $pdo->query($request)->fetch()[0];
    $request = "SELECT COUNT(*) FROM `pictures` WHERE USER_ID='$id'";
    $nb_images = $pdo->query($request)->fetch()[0];
    $request = "SELECT link FROM `pictures` WHERE USER_ID='$id' ORDER BY id DESC";
    $images = $pdo->query($request);
    foreach ($images as $image) {
        $officiel = str_replace(' ', '+', $image[0]);
        echo "<img class='officiel' src='$officiel'/>";
    }
?>