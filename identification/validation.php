<?php
session_start();
require_once('../config/pdo.php');

$id = $_GET['id'];
$request = "UPDATE `users` SET validate='1' WHERE id=$id";
$pdo->exec($request);

echo "NICE, your account is validated !! You will be redirected in 5 seconds to the main page (:"
?>
<html>
    <head>
        <meta http-equiv="refresh" content="5; URL='../index.php'"/>
    </head>
</html>