<html>
    <body>
        <form action="./reset_password.php" method="post">
            <!-- <input type="password" name="last_password" placeholder="Last password" required> -->
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Reset password">
        </form>
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
    if ($pdo->exec($request) > 0) {
        echo "Password changed succesfully, you will be redirected to the main page !";
?>
    <html>
        <head>
            <meta http-equiv="refresh" content="5; URL='../index.php'"/>
        </head>
    </html>
<?php
    }
    echo "Wrong password.";
}
else
    $_SESSION['id'] = $_GET['id'];
?>