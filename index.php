<?php
session_start();
require_once("config/pdo.php");
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
        <div class="container_pictures">
            <?php
                if (isset($_GET['page']) && (is_numeric($_GET['page']) && $_GET['page'] > 0)) {
                    if (!isset($_GET['page']))
                        $choice = 0;
                    else
                        $choice = ($_GET['page']-1)*9;
                    $request = "SELECT link FROM `pictures` ORDER BY id DESC LIMIT $choice, 9";
                    $images = $pdo->query($request);
                    foreach ($images as $image) {
                        $officiel = str_replace(' ', '+', $image[0]);
                        // if ($officiel != NULL)
                            echo "<img class='picture' src='$officiel'/>";
                    }
                }
                else {
                    ?>
                    <head>
                        <meta http-equiv="refresh" content="0; URL='./index.php?page=1'"/>
                    </head>
                <?php
                }
            ?>
        </div>
        <div class="container_pagination">
            <div class="pagination">
            <!-- <a href="#">&laquo;</a> -->
            <?php
                $request = "SELECT COUNT(*) FROM `pictures` WHERE user_id";
                $nb_images = $pdo->query($request)->fetch()[0];
                $nb_pages = $nb_images/9;
                $index = 1;
                for ($nb_pages; $nb_pages > 0; $nb_pages--) {
                    if (isset($_GET['page']) && $_GET['page'] == $index)
                        echo "<a class='active' href='index.php?page=$index'>".$index++."</a>";
                    else
                        echo "<a href='index.php?page=$index'>".$index++."</a>";
                }
            ?>
            <!-- <a href="#">&raquo;</a> -->
            </div>
        </div>
        <div class="footer">
            <div class="text_footer">© jcharloi 2018</div>
        </div>
    </body>
</html>