<?php
require_once('../config/pdo.php');

$id = $_GET['id'];
$request = "UPDATE `users` SET validate='1' WHERE id=$id";
$pdo->exec($request);

echo "NICE, your account is validated !! You will be redirected in 5 seconds to the connection page (:"
?>
<html>
    <head>
        <meta http-equiv="refresh" content="5; URL=http://localhost:8080/Camagru/git/identification/connection.php"/>
    </head>
</html>