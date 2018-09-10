<html>
    <head>
        <link rel="stylesheet" type="text/css" href="./registration.css">
        <meta charset = "utf-8">
    </head>
    <body>
        <div class="topbar">
            <h1 class="title">Sign up !</h1>
        </div>
        <div class="container">
            <div class="cadre">
                <form class="rfi" action="./registration.php" method="post">
                    User name *<br/>
                    <input type="text" name="name_request" required><br/>
                    Email adress *<br/>
                    <input type="email" name="email_request" required><br/>
                    <!-- securiser un peu le mdp genre min-length -->
                    Password *<br/>
                    <input name="password_request" type="password" required><br/>
                    <input class="submit" type="submit" value="Register my account">
                </form>
             </div>
        </div>
    </body>
</html>

<?php
session_start();
require_once('../config/pdo.php');

function check_existing_user($pdo, $name) {
    $request = "SELECT name FROM `users`";
    $users = $pdo->query($request);
    if ($users) {
        foreach ($users as $user) {
            if ($user[0] == $name) {
                return true;
            }
        }
    }
    return false;
}

function check_existing_adress($pdo, $adress) {
    if (filter_var($adress, FILTER_VALIDATE_EMAIL) == false) {
        return true;
    }
    $request = "SELECT email FROM `users`";
    $emails = $pdo->query($request);
    if ($emails) {
        foreach ($emails as $email) {
            if ($email[0] == $adress) {
                return true;
            }
        }
    }
    return false;
}

function add_user_database($pdo, $name, $password, $adress) {
    $password = hash('whirlpool', $password);
    $hash_mail = hash('whirlpool', $adress);
    $request = "INSERT INTO `users` (NAME, PASSWORD, EMAIL, HASH_MAIL, VALIDATE) VALUES ('$name', '$password', '$adress', '$hash_mail', 0)";
    $pdo->exec($request);
    //echo "User added";
}

function send_mail($pdo, $name) {
    $request = "SELECT id FROM `users` WHERE name='$name'";
    $id = $pdo->query($request);
    $id = $id->fetch()[0];
    $lien = "http://localhost:8080/Camagru/git/identification/validation.php?id=".$id;
    $message = "Welcome ".$_POST['name_request'].
    " !\r\n\nPlease click on the link below to validate your account and join the adventure :\r\n".$lien." ! \r\nSee you soon :D";
    $subject = "Registration for Camagru";
    $to = $_POST['email_request'];
    if (mail($to, $subject, utf8_decode($message)))
        echo "Please check your emails to validate your adress, do not forget to check your unwanted messages (:";
    else {
        echo "Unfortunately, sending email did not work.. ):";
    }
}

if (isset($_POST['name_request']) && isset($_POST['email_request']) && isset($_POST['password_request'])) {
    //INJECTIONS SQL
    if (!check_existing_user($pdo, $_POST['name_request']) && !check_existing_adress($pdo, $_POST['email_request'])) {
        add_user_database($pdo, $_POST['name_request'], $_POST['password_request'], $_POST['email_request']);
        send_mail($pdo, $_POST['name_request']);
    }
    else {
        echo "This name/adress is/are already used, or the adress is invalid format, please change.";
    }
}
?>