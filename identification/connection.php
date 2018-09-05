<html>
    <head>
        <link rel="stylesheet" type="text/css" href="./connection.css">
        <meta charset = "utf-8">
    </head>
    <body>
        <h1>Sign in !</h1>
        <form action="./connection.php" method="post">
            <input type="text" name="name" placeholder="User name" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Enter the site">
        </form>
        <form action="./connection.php" method="post">
            <input type="submit" name="forgot" value="Forgot your password ?">
        </form>
    </body>
</html>

<?php
session_start();
require_once('../config/pdo.php');
//INJECTIONS SQL

function show_reset_password() {
    ?>
    <html>
        <body>
        <form action="./connection.php" method="post">
            <input type="text" name="email" placeholder="Enter your email adress" required>
            <input type="submit" value="Get my reset email">
        </form>
        </body>
    </html>
    <?php
}

function check_existing_user($pdo, $name, $password) {
    $request = "SELECT * FROM `users`";
    $users = $pdo->query($request);
    if ($users) {
        foreach ($users as $user) {
            if ($user[1] == $name && $user[2] == hash('whirlpool', $password) && $user[5] == 1)
                return true;
        }
    }
    return false;
}

function check_existing_adress($pdo, $adress) {
    $request = "SELECT * FROM `users`";
    $users = $pdo->query($request);
    if ($users) {
        foreach ($users as $user) {
            if ($user[3] == $adress && $user[5] == 1)
                return true;
        }
    }
    return false;
}

function send_mail($pdo, $adress) {
    $request = "SELECT hash_mail FROM `users` WHERE email='$adress'";
    $hash = $pdo->query($request);
    $hash = $hash->fetch()[0];
    $lien = "http://localhost:8080/Camagru/git/identification/reset_password.php?id=".$hash;
    $message = "Welcome back !\r\n\nIt seems like you forgot your password. If you need to change it, please click on the link below :\r\n".$lien." ! \r\nSee you soon :D";
    $subject = "Forgotten password for Camagru";
    $to = $adress;
    if (mail($to, $subject, utf8_decode($message)))
        echo "Please check your emails to reset your password, do not forget to check your unwanted messages (:";
    else {
        echo "Unfortunately, sending email did not work.. ):";
    }
}

if (isset($_POST['email'])) {
    if (check_existing_adress($pdo, $_POST['email'])) {
        send_mail($pdo, $_POST['email']);
    }
    else {
        show_reset_password();
        echo "Unfortunately, this account does not exist. Are you sure you have validated it ?<br/>
        Or perhaps you wrote your adress incorrectly.";
    }
}

if (isset($_POST['name']) && isset($_POST['password'])) {
    if (check_existing_user($pdo, $_POST['name'], $_POST['password'])) {
    ?>
        <html>
            <head>
                <meta http-equiv="refresh" content="0; URL=http://localhost:8080/Camagru/git/index.php"/>
            </head>
        </html>
    <?php
    }
    else
        echo "Connection failed, verify your name/password. Be sure you validated your account first.";
}

if (isset($_POST['forgot'])) {
    show_reset_password();
}
?>