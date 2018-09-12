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
                    <span class="text">User name *</span><br/>
                    <input class="input" type="text" name="name_request" maxlength="20" required><br/>
                    <span class="text">Email adress *</span><br/>
                    <input class="input" type="email" name="email_request" maxlength="50" required><br/>
                    <!-- securiser un peu le mdp genre min-length -->
                    <span class="text">Password *</span><br/>
                    <input class="input" name="password_request" type="password" required><br/>
                    <input class="submit" type="submit" value="Register"><br/>
                    <?php
                        if (isset($_GET['id']) && $_GET['id'] == 'account')
                            echo "<br/><center class='wrong'>This name/adress is/are already used, please change.</center>";
                        else if (isset($_GET['id']) && $_GET['id'] == 'mail')
                            echo "<br/><center class='good'>Please check your emails to validate your adress, do not forget to check your unwanted messages (:</center>";
                        else if (isset($_GET['id']) && $_GET['id'] == 'error')
                            echo "<br/><center class='wrong'>Unfortunately, sending email did not work.. ):</center>";
                    ?>
                </form>
             </div>
        </div>
        <div class="footer">
            <div class="text_footer">© jcharloi 2018</div>
        </div>
    </body>
</html>

<?php
require_once('../config/pdo.php');
session_start();

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
    $request = "INSERT INTO `users` (NAME, PASSWORD, EMAIL, VALIDATE) VALUES ('$name', '$password', '$adress', 0)";
    $pdo->exec($request);
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
    if (mail($to, $subject, utf8_decode($message))) {
        ?>
        <head>
            <meta http-equiv="refresh" content="0; URL='./registration.php?id=mail'"/>
        </head>
        <?php
        //echo "Please check your emails to validate your adress, do not forget to check your unwanted messages (:";
    }
    else {
        ?>
        <head>
            <meta http-equiv="refresh" content="0; URL='./registration.php?id=error'"/>
        </head>
        <?php
        //echo "Unfortunately, sending email did not work.. ):";
    }
}

if (isset($_POST['name_request']) && isset($_POST['email_request']) && isset($_POST['password_request'])) {
    //INJECTIONS SQL
    if (!check_existing_user($pdo, $_POST['name_request']) && !check_existing_adress($pdo, $_POST['email_request'])) {
        add_user_database($pdo, $_POST['name_request'], $_POST['password_request'], $_POST['email_request']);
        send_mail($pdo, $_POST['name_request']);
    }
    else {
        ?>
        <head>
            <meta http-equiv="refresh" content="0; URL='./registration.php?id=account'"/>
        </head>
        <?php
    }
}
?>