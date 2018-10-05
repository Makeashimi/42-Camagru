<?php
require_once("config/pdo.php");
session_start();

if (isset($_SESSION['user'])) {
    if (!empty($_POST['picture_id'])) {
        $id = $_POST['picture_id'];
        //requete sql pour recuperer l'user_id selon l'id de l'image
        $request = "SELECT name, nb FROM `loves` WHERE id='$id' ORDER BY id DESC LIMIT 1";
        //$res = $pdo->query($request)->fetch();
        //echo $res;
        if ($_SESSION['user'] == 'Shimi')
            echo $res[1];
        else
            echo $res[1]." Ko";
    }
    
    if (!empty($_POST['like']) && !empty($_POST['id'])) {
        $picture_id = $_POST['id'];
        $name = $_SESSION['user'];
        $request = "SELECT nb FROM `loves` WHERE id='$picture_id' ORDER BY id DESC LIMIT 1";
        $nb = $pdo->query($request)->fetch()[0];
        $nb = $nb + 1;
        $request = "INSERT INTO `loves` (id, nb, name) VALUES ($id, $nb, '$name')";
        $pdo->exec($request);
        echo $nb;
    }
}
else if (!isset($_SESSION['user']) && !empty($_POST['picture_id'])) {
    $id = $_POST['picture_id'];
    $request = "SELECT love FROM `pictures` WHERE id=$id";
    $like = $pdo->query($request)->fetch()[0];
    echo $like." Ko";
}
else
    echo "Fail";
?>