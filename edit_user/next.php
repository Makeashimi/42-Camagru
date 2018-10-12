<?php
require_once('../config/pdo.php');
session_start();

function check_not_existing_user($pdo, $index, $value) {
    $request = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE $index=':value'");
    $params = array(':value' => $value);
    $request->execute($params);
    $users = $request->fetch();
    if ($users[0] > 0)
        return false;
    return true;
}

if (isset($_SESSION['user'])) {
    $value = "";
    $new_name = "";

    if (isset($_POST['new_name']) && $_POST['new_name'] != null) {
        if (check_not_existing_user($pdo, 'name', $_POST['new_name'])) {
            $new_name = $_POST['new_name'];
            $value = $value."name=:name";
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
            $value = $value."email=:email";
        }
        else {
            ?>
                <html> <head> <meta http-equiv="refresh" content="0; URL='./profile.php?id=fail'"/> </head> </html>
            <?php
        }
    }

    if (isset($_POST['new_password']) && $_POST['new_password'] != null) {
        $id = $_SESSION['id_user'];
        $request = "SELECT * FROM `users` WHERE id='$id'";
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
        $id = $_SESSION['id_user'];
        $actual_password = hash('whirlpool', $_POST['last_password']);
        $request = "SELECT password FROM `users` WHERE id='$id'";
        $user_password = $pdo->query($request)->fetch()[0];
        // echo "$user_password, $actual_password";
        if ($user_password != $actual_password) {
        ?>
            <html> <head> <meta http-equiv='refresh' content="0; URL='./profile.php?id=password'"/> </head> </html>
        <?php
            return ;
        }
        $request = $pdo->prepare("UPDATE `users` SET $value WHERE id='$id' AND password='$actual_password'");
        // echo "UPDATE `users` SET $value WHERE name='$name' AND password='$actual_password'";
        if (!empty($_POST['new_name'])) {
            $_SESSION['user'] = $_POST['new_name'];
            $request->bindParam(':name', $new_name);
            $new_name = $_POST['new_name'];
        }
        if (!empty($_POST['new_email'])) {
            $request->bindParam(':email', $mail);
            $mail = $_POST['new_email'];
        }
        $request->execute();
        ?>
            <html> <head> <meta http-equiv="refresh" content="0; URL='./profile.php?id=success'"/> </head> </html>
        <?php 
    }

    if (isset($_POST['notif'])) {
        $id = $_SESSION['id_user'];
        $request = "UPDATE `users` SET notif='1' WHERE id='$id'";
        $pdo->exec($request);
        ?>
            <html> <head> <meta http-equiv="refresh" content="0; URL='./profile.php?id=success'"/> </head> </html>
        <?php 
    }
    else {
        $id = $_SESSION['id_user'];
        $request = "UPDATE `users` SET notif='0' WHERE id='$id'";
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