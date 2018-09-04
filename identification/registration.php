<?php
require_once('../config/pdo.php');

function check_existing_user($pdo, $name) {
    $request = "SELECT name FROM `users`";
    $users = $pdo->query($request);
    if ($users) {
        foreach ($users as $user) {
            if ($user[0] == $name) {
                return false;
            }
        }
    }
    return true;
}

function check_existing_adress($pdo, $adress) {
    $request = "SELECT email FROM `users`";
    $emails = $pdo->query($request);
    if ($emails) {
        foreach ($emails as $email) {
            if ($email[0] == $adress) {
                return false;
            }
        }
    }
    return true;
}

function add_user_database($pdo, $name, $password, $adress) {
    $password = hash('whirlpool', $password);
    $request = "INSERT INTO `users` (NAME, PASSWORD, EMAIL, VALIDATE) VALUES ('$name', '$password', '$adress', 0)";
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

if (!isset($_POST['name_request']) && !isset($_POST['email_request']) && !isset($_POST['password_request'])) {
?>
    <html>
    <head>
        <link rel="stylesheet" type="text/css" href="./registration.css">
        <meta charset = "utf-8">
    </head>
    <body>
    <h1>Sign up !</h1>
    <form action="./registration.php" method="post">
        <input type="text" name="name_request" placeholder="User name" required><br/>
        <input type="text" name="email_request" placeholder="Email adress" required><br/>
        <input class="password" name="password_request" placeholder="Password" type="password" required ><br/>
        <input type="submit" value="Validate">
    </form>
    </body>
    </html>
<?php
}
else {
    //INJECTIONS SQL
    if (check_existing_user($pdo, $_POST['name_request']) && check_existing_adress($pdo, $_POST['email_request'])) {
        add_user_database($pdo, $_POST['name_request'], $_POST['password_request'], $_POST['email_request']);
        send_mail($pdo, $_POST['name_request']);
    }
    else {
        echo "This name or the adress is/are already used, please change. You will be redirected to the registration page in 5 seconds.";
        $_POST['name_request'] = $_POST['email_request'] = $_POST['password_request'] = null;
        ?>
        <html>
        <head>
            <meta http-equiv="refresh" content="5; URL=http://localhost:8080/Camagru/git/identification/registration.php"/>
        </head>
        </html>
        <?php
    }
}
?>