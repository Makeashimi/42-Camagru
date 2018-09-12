<?php
require_once('../config/pdo.php');
session_start();

function check_existing_adress($pdo, $hash) {
    $request = "SELECT * FROM `users`";
    $users = $pdo->query($request);
    if ($users) {
        foreach ($users as $user) {
            if ($user[4] == $hash)
                return true;
        }
    }
    return false;
}

if ((isset($_GET['id']) && check_existing_adress($pdo, $_GET['id'])) || isset($_SESSION['id'])) {
?>
    <html>
        <head>
            <link rel="stylesheet" type="text/css" href="./reset_password.css">
            <meta charset = "utf-8">
            <?php
                if (isset($_GET['value']) && $_GET['value'] == 'success')
                    echo "<meta http-equiv='refresh' content=\"5; URL='../index.php\"/>";
            ?>
        </head>
        <body>
            <div class="topbar">
                <h1 class="title">Reset your password</h1>
            </div>
            <div class="container">
                <div class="cadre">
                    <form class="rfi" action="./reset_password.php" method="post">
                        <span class="text">Enter your new password *</span><br/>
                        <input class="input" type="password" name="password" required><br/>
                        <input class="submit" type="submit" value="Change it"><br/>
                        <?php
                            if (isset($_GET['value']) && $_GET['value'] == 'success')
                                echo "<br/><center class='good'>Password changed succesfully, you will be redirected to the main page in 5 seconds ! :3</center>";
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
    if (isset($_POST['password'])) {
        $id = $_SESSION['id'];
        // $lpassword = hash('whirlpool', $_POST['last_password']); 
        $password = hash('whirlpool', $_POST['password']);
        $request = "UPDATE `users` SET password='$password', hash_mail='null' WHERE hash_mail='$id'";
        unset($_SESSON['id']);
        $pdo->exec($request);
        ?>
            <html>
                <head>
                    <meta http-equiv="refresh" content="0; URL='./reset_password.php?value=success'"/>
                </head>
            </html>
        <?php
    }
    else
        $_SESSION['id'] = $_GET['id'];
}
else
    echo "This page does not exist anymore, sorry. (;";