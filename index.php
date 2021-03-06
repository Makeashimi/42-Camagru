<?php
session_start();
require_once("config/pdo.php");
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="./index.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <meta charset = "utf-8">
    </head>
    <body>
        <div id='transparant' onClick='hide_div()'></div>
        <div class="topbar">
            <h1 class="title">Welcome to my Camagru !</h1>
                <div class="container">
                    <?php if (isset($_SESSION['id_user'])) {?>
                        <a class="text" href="montage/montage.php"><button class="button_top">Montage</button></a>
                        <a class="text" href="edit_user/profile.php"><button class="button_top"><?php echo htmlspecialchars($_SESSION['user']) ?></button></a>
                        <a class="text" href="identification/disconnect.php"><button class="button_top">Disconnect</button></a>
                    <?php }
                    else { ?>
                        <button class="button_top">Identification</button>
                        <div class="container-child">
                            <a class="text" href="identification/connection.php">Sign in !</a>
                            <a class="text" href="identification/registration.php">No account yet ?</a>
                        </div>
                    <?php } ?>
                </div>
        </div>
        <div class="container_pictures">
            <?php require('pictures.php') ?>
        </div>
        <div class="container_pagination">
            <?php require('pagination.php') ?>
        </div>
        <?php require('dialogbox.php') ?>
        <div class="footer">
            <div class="text_footer">© jcharloi 2018</div>
        </div>
    </body>
</html>