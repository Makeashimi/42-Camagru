<?php
//INJECTIONS SQL
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="./connection.css">
        <meta charset = "utf-8">
    </head>
    <body>
    <h1>Sign in !</h1>
    <form action="../main.php" method="post">
        <input type="text" name="user" placeholder="User name" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Validate">
    </form>
    </body>
</html>