<?php
//GALERIE PHOTOS ET MENU DEROULANT
//savoir qu'une personne est connectée ou non
session_start();
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="./index.css">
        <meta charset = "utf-8">
    </head>
    <body>
        <div class="topbar">
            <h1 class="title"> Welcome to my Camagru !</h1>
                <div class="container">
                    <button class="identification">Identification</button>
                    <div class="container-child">
                        <a class="text" href="identification/connection.php">Sign in !</a>
                        <a class="text" href="identification/registration.php">No account yet ?</a>
                    </div>
                </div>
        </div>
    </body>
</html>