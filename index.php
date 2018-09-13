<?php
//GALERIE PHOTOS ET MENU DEROULANT
session_start();
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="./index.css">
        <meta charset = "utf-8">
    </head>
    <body>
        <div class="topbar">
            <h1 class="title">Welcome to my Camagru !</h1>
                <div class="container">
                    <?php if (isset($_SESSION['user'])) {?>
                        <a class="text" href="montage/montage.php"><button class="button_top">Montage</button></a>
                        <a class="text" href="edit_user/profile.php"><button class="button_top"><?php echo $_SESSION['user'] ?></button></a>
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
        <div class="footer">
            <div class="text_footer">© jcharloi 2018</div>
        </div>
    </body>
</html>