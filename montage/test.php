<?php 
session_start();
require_once("../config/pdo.php");

if (!empty($_POST['choice']) && !empty($_POST['image'])) {
    $id = $_SESSION['id_user'];
    $image = $_POST['image'];
    $choice = $_POST['choice'];
    // Monter la photo ici, l'inserer dans la bdd
    $request = "INSERT INTO `pictures` (USER_ID, CHOICE, LINK, VALIDATE) VALUES ('$id', '$choice', '$image', 0)";
    $pdo->exec($request);
    $image = str_replace(' ', '+', $image);
    echo $image;
}
else
    echo "Fail";

if (isset($_POST['validate'])) {
    $id = $_SESSION['id_user'];
    $request = "UPDATE `pictures` SET validate='1' WHERE user_id='$id' ORDER BY id DESC LIMIT 1";
    $pdo->exec($request);
}

// if (isset($_POST['choice']) && isset($_POST['file'])) {
//     $id = $_SESSION['id_user'];
//     $file = $_POST['file'];
//     $choice = $_POST['choice'];
//     // Monter la photo ici, l'inserer dans la bdd
//     $request = "INSERT INTO `pictures` (USER_ID, CHOICE, LINK, VALIDATE) VALUES ('$id', '$choice', '$file', 0)";
//     $pdo->exec($request);
//     echo $file;
// }
?>