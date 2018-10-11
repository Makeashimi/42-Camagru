<?php
require_once('../config/pdo.php');
session_start();

if (isset($_SESSION['user'])) {
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

    // echo $_POST['notif'];
    if (isset($_POST['notif'])) {
        $name = $_SESSION['user'];
        $request = "UPDATE `users` SET notif='1' WHERE name='$name'";
        $pdo->exec($request);
        ?>
            <html> <head> <meta http-equiv="refresh" content="0; URL='./profile.php?id=success'"/> </head> </html>
        <?php 
    }
    else {
        $name = $_SESSION['user'];
        $request = "UPDATE `users` SET notif='0' WHERE name='$name'";
        $pdo->exec($request);
        ?>
            <html> <head> <meta http-equiv="refresh" content="0; URL='./profile.php?id=success'"/> </head> </html>
        <?php
    }
}
else {
    ?>
        <html> <head> <meta http-equiv="refresh" content="0; URL='./profile.php'"/> </head> </html>
    <?php
}
?>