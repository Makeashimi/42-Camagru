<?php
require_once('../config/pdo.php');
session_start();

function check_not_existing_user($pdo, $index, $value) {
    $request = "SELECT COUNT(*) FROM `users` WHERE $index='$value'";
    $users = $pdo->query($request)->fetch();
    if ($users[0] > 0)
        return false;
    return true;
}

if (isset($_SESSION['user'])) {
?>
    <html>
        <head>
            <link rel="stylesheet" type="text/css" href="../index.css">
            <link rel="stylesheet" type="text/css" href="./profile.css">
            <meta charset = "utf-8">
        </head>
        <body>
            <div class="topbar">
                <h1 class="title">Edit your profile</h1>
                <div class="container">
                    <a class="text" href="../index.php"><button class="button_top">Gallery</button></a>
                    <a class="text" href="../montage/montage.php"><button class="button_top">Montage</button></a>
                    <a class="text" href="../identification/disconnect.php"><button class="button_top">Disconnect</button></a>
                </div>
            </div>
            <div class="container_cadre">
                <div class="cadre">
                    <form class="rfi" action="./profile.php" method="post">
                        <span class="text">New user name</span><br/>
                        <input class="input" type="text" name="new_name" maxlength="20"><br/>
                        <span class="text">New email adress</span><br/>
                        <input class="input" type="email" name="new_email" maxlength="50"><br/>
                        <!-- securiser un peu le mdp genre min-length -->
                        <span class="text">New password</span><br/>
                        <input class="input" type="password" name="new_password"><br/>
                        <span class="text">Actual password*</span><br/>
                        <input class="input" type="password" name="last_password" required>
                        <input class="submit" type="submit" value="Edit"><br/>

                        <?php
                            if (isset($_GET['id']) && $_GET['id'] == 'success')
                                echo "<br/><center class='good'>Informations changed succesfully ! :3</center>";
                            if (isset($_GET['id']) && $_GET['id'] == 'password')
                                echo "<br/><center class='wrong'>Wrong password.</center>";
                            if (isset($_GET['id']) && $_GET['id'] == 'fail')
                                echo "<br/><center class='wrong'>These informations are already taken.</center>";
                                if (isset($_GET['id']) && $_GET['id'] == 'same')
                                echo "<br/><center class='wrong'>You can't change your password by the same.</center>";
                        ?>

                    </form>
                </div>
            </div>
            <!-- <div class="footer">
                <div class="text_footer">© jcharloi 2018</div>
            </div>  -->
        </body>
    </html>
<?php

    $value = "";
    $new_name = "";

    if (isset($_POST['new_name']) && $_POST['new_name'] != null) {
        if (check_not_existing_user($pdo, 'name', $_POST['new_name'])) {
            $new_name = $_POST['new_name'];
            $value = $value."name='$new_name'";
        }
        else {
            ?>
                <html> <head> <meta http-equiv="refresh" content="0; URL='./profile.php?id=fail'"/> </head> </html>
            <?php
        }
    }

    if (isset($_POST['new_email']) && $_POST['new_email'] != null) {
        if (check_not_existing_user($pdo, 'email', $_POST['new_email'])) {
            $new_email = $_POST['new_email'];
            if (!empty($value))
                $value = $value.", ";
            $value = $value."email='$new_email'";
        }
        else {
            ?>
                <html> <head> <meta http-equiv="refresh" content="0; URL='./profile.php?id=fail'"/> </head> </html>
            <?php
        }
    }

    if (isset($_POST['new_password']) && $_POST['new_password'] != null) {
        $name = $_SESSION['user'];
        $request = "SELECT * FROM `users` WHERE name='$name'";
        $infos = $pdo->query($request);
        if ($infos->fetch()[2] != hash('whirlpool', $_POST['new_password'])) {
            $new_password = hash('whirlpool', $_POST['new_password']);
            if (!empty($value))
                $value = $value.", ";
            $value = $value."password='$new_password'";
        }
        else {
            ?>
                <html> <head> <meta http-equiv="refresh" content="0; URL='./profile.php?id=same'"/> </head> </html>
            <?php
        }
    }

    if ($value != "") {
        $name = $_SESSION['user'];
        $actual_password = hash('whirlpool', $_POST['last_password']);
        $request = "UPDATE `users` SET $value WHERE name='$name' AND password='$actual_password'";
        if ($pdo->exec($request) > 0) {
            if ($new_name != "")
                $_SESSION['user'] = $new_name;
            ?>
            <html> <head> <meta http-equiv="refresh" content="0; URL='./profile.php?id=success'"/> </head> </html>
            <?php
        }
        else {
            ?>
            <html> <head> <meta http-equiv="refresh" content="0; URL='./profile.php?id=password'"/> </head> </html>
            <?php
        }
    }
}
else {
    echo "You need to sign in to access this page. (;";
}
?>