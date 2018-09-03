<?php
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="./connexion.css">
        <meta charset = "utf-8">
    </head>
    <body>
    <h1>Connecte toi !</h1>
    <form action="../main.php" method="post">
        <input type="text" name="user" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="submit">
    </form>
    </body>
</html>