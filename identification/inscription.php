<?php
if (!isset($_POST['user_request']) && !isset($_POST['email_request']) && !isset($_POST['password_request'])) {
?>
    <html>
    <head>
        <link rel="stylesheet" type="text/css" href="./inscription.css">
        <meta charset = "utf-8">
    </head>
    <body>
    <h1>Inscris toi !</h1>
    <form action="./inscription.php" method="post">
        <input type="text" name="user_request" placeholder="Nom d'utilisateur" required><br/>
        <input type="text" name="email_request" placeholder="Adresse email" required><br/>
        <input class="password" name="password_request" placeholder="Mot de passe" type="password" required><br/>
        <input type="submit">
    </form>
    </body>
    </html>
<?php
}
else {
    //VERIFIER QUE L'UTILISATEUR N'EXISTE PAS DEJA !!
    
    $lien = "http://localhost:8080/Camagru/git/identification/validation.php?id=";
    $message = "Bienvenue ".$_POST['user_request'].
    " !\r\n\nJe t'invite à cliquer sur le lien en dessous pour valider ton adresse email et rejoindre l'aventure :\r\n ".$lien." ! \r\nA tout de suite :D";
    $subject = "Inscription pour Camagru";
    $to = $_POST['email_request'];
    // if (mail($to, $subject, utf8_decode($message)))
    //     echo "Je t'invite à vérifier tes mails pour valider ton adresse, n'oublie pas de regarder dans les spams (:";
    // else {
    //     echo "Malheureusement, l'envoi d'email n'a pas fonctionné.. ):";
    // }
}
?>