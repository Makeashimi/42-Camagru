<?php 
session_start();
require_once("../config/pdo.php");

    if (isset($_POST['choice']) && isset($_POST['image'])) {
        $id = $_SESSION['id_user'];
        $image = $_POST['image'];
        $choice = $_POST['choice'];
        // Monter la photo ici, l'inserer dans la bdd
        $request = "INSERT INTO `pictures` (USER_ID, CHOICE, LINK) VALUES ('$id', '$choice', '$image')";
        $pdo->exec($request);
        echo $image;
    }
    ?>