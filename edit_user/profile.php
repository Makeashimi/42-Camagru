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
                    <form class="rfi" action="./next.php" method="post">
                        <span class="text">New user name</span><br/>
                        <input class="input" type="text" name="new_name" maxlength="20"><br/>
                        <span class="text">New email adress</span><br/>
                        <input class="input" type="email" name="new_email" maxlength="50"><br/>
                        <!-- securiser un peu le mdp genre min-length -->
                        <span class="text">New password</span><br/>
                        <input class="input" type="password" name="new_password"><br/>
                        <span class="text">Actual password*</span><br/>
                        <input class="input" type="password" name="last_password" required>
                        <?php
                            $name = $_SESSION['user'];
                            $request = "SELECT notif FROM `users` WHERE name='$name'";
                            $notif = $pdo->query($request)->fetch()[0];
                            if ($notif == '0') {
                                echo "<input type='checkbox' name='notif' unchecked>Desactive notification when a comment is received<br/>";
                            } else if ($notif == '1') {
                                echo "<input type='checkbox' name='notif' checked>Desactive notification when a comment is received<br/>";
                            }
                        ?>
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
            <div class="footer">
                <div class="text_footer">© jcharloi 2018</div>
            </div> 
        </body>
    </html>
<?php

    
}
else {
    echo "You need to sign in to access this page. (;";
}
?>