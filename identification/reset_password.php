<html>
    <head>
        <link rel="stylesheet" type="text/css" href="./reset.css">
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
                    <!-- <input type="password" name="last_password" placeholder="Last password" required> -->
                    <input class="input" type="password" name="password" required><br/>
                    <input class="submit" type="submit" value="Change it"><br/>
                    <?php
                        if (isset($_GET['value']) && $_GET['value'] == 'success')
                            echo "<br/><center class='good'>Password changed succesfully, you will be redirected to the main page in 5 seconds ! :3</center>";
                    ?>
                </form>
            </div>
        </div>
    </body>
</html>

<?php
require_once('../config/pdo.php');
session_start();

if (isset($_POST['password'])) {
    $id = $_SESSION['id'];
    // $lpassword = hash('whirlpool', $_POST['last_password']); 
    $password = hash('whirlpool', $_POST['password']);
    $request = "UPDATE `users` SET password='$password' WHERE hash_mail='$id'";
    //if ($pdo->exec($request) > 0) {
    ?>
        <html>
            <head>
                <meta http-equiv="refresh" content="0; URL='./reset_password.php?value=success'"/>
            </head>
        </html>
    <?php
    //}
}
else
    $_SESSION['id'] = $_GET['id'];
?>