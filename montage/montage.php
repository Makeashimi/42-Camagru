<?php
session_start();

if (isset($_SESSION['user'])) {
?>
    <html>
        <head>
            <link rel="stylesheet" type="text/css" href="../index.css">
            <link rel="stylesheet" type="text/css" href="./montage.css">
            <meta charset = "utf-8">
        </head>
        <body>
            <div class="topbar">
                <h1 class="title">Time to take a picture</h1>
                <div class="container">
                    <a class="text" href="../index.php"><button class="button_top">Gallery</button></a>
                    <a class="text" href="../edit_user/profile.php"><button class="button_top"><?php echo $_SESSION['user'] ?></button></a>
                     <a class="text" href="../identification/disconnect.php"><button class="button_top">Disconnect</button></a>
                </div>
            </div>
        </body>
        <?php require('webcam.php') ?>
        <!-- <div class="footer">
            <div class="text_footer">© jcharloi 2018</div>
        </div> -->
    </html>
<?php }
else {
    echo "You need to sign in to access this page. (;";
}
?>