<?php
require_once('../config/pdo.php');
session_start();

$request = $pdo->prepare("UPDATE `users` SET validate='1' WHERE id=:id");
$params = array(':id' => $_GET['id']);
$request->execute($params);
if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
}
if (isset($_SESSION['id_user'])) {
    unset($_SESSION['id_user']);
}

echo "NICE, your account is validated !! You will be redirected in 5 seconds to the main page (:"
?>
<html>
    <head>
        <meta http-equiv="refresh" content="5; URL='../index.php'"/>
    </head>
</html>