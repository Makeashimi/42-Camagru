<?php
session_start();
require_once("../config/pdo.php");

if (isset($_SESSION['user'])) {
?>
    <html>
        <head>
            <link rel="stylesheet" type="text/css" href="../index.css">
            <link rel="stylesheet" type="text/css" href="./montage.css">
            <meta charset = "utf-8">
        </head>
        <body>
            <div class="topbar">
                <h1 class="title">Time to take a picture</h1>
                <div class="container">
                    <a class="text" href="../index.php"><button class="button_top">Gallery</button></a>
                    <a class="text" href="../edit_user/profile.php"><button class="button_top"><?php echo $_SESSION['user'] ?></button></a>
                     <a class="text" href="../identification/disconnect.php"><button class="button_top">Disconnect</button></a>
                </div>
            </div>
            <div class="container_body">
                <div class="video">
                    <?php require('filters.php') ?>
                    <?php require('webcam.php') ?>
                </div>
                <div class="image">
                    <?php require('gallery.php'); ?>
                </div>
            </div>
        </body>
        <div class="footer">
            <div class="text_footer">© jcharloi 2018</div>
        </div>
    </html>
<?php

    if (isset($_POST['choice']) && isset($_POST['image'])) {
        $id = $_SESSION['id_user'];
        $image = $_POST['image'];
        $choice = $_POST['choice'];
        // Monter la photo ici, l'inserer dans la bdd
        $request = "INSERT INTO `pictures` (USER_ID, CHOICE, LINK) VALUES ('$id', '$choice', '$image')";
        $pdo->exec($request);
        echo "salut";
    }

}
else {
    echo "You need to sign in to access this page. (;";
}
?>