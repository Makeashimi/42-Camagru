<?php
require_once('database.php');
try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
} catch (PDOException $error) {
    print "Erreur avec la connexion !: " . $error->getMessage() . "<br/>";
    die();
}

try {
    $requete = "DROP DATABASE IF EXISTS `$DB_NAME`";
    $dbh->prepare($requete)->execute();
    echo "Ancienne base de données supprimée ... <br/>";
} catch (PDOException $error) {
    print "Erreur avec la suppression de l'ancienne base de données !: " . $error->getMessage() . "<br/>";
    die();
}

try {
    $requete = "CREATE DATABASE`$DB_NAME` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
    $dbh->prepare($requete)->execute();
    echo "... Nouvelle base de données camagru creée ... <br/>";
} catch (PDOException $error) {
    print "Erreur avec la création de la base de données !: " . $error->getMessage() . "<br/>";
    die();
}

$dbh->exec("use $DB_NAME");

try {
    $requete = "CREATE TABLE `employees`";
    $dbh->exec($requete);
    echo "... Nouvelle table utilisateur creée ... <br/>";
} catch (PDOException $error) {
    print "Erreur avec la nouvelle table !: " . $error->getMessage() . "<br/>";
    die();
}

?>