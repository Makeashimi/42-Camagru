<?php
require_once('database.php');

try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
    print "Error while connecting !: " . $error->getMessage() . "<br/>";
    die();
}

try {
    $request = "DROP DATABASE IF EXISTS `$DB_NAME`";
    $dbh->exec($request);
    echo "Previous database removed ... <br/>";
} catch (PDOException $error) {
    print "Error while removing previous database !: " . $error->getMessage() . "<br/>";
    die();
}

try {
    $request = "CREATE DATABASE `$DB_NAME` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
    $dbh->exec($request);
    echo "... New camagru database created ... <br/>";
} catch (PDOException $error) {
    print "Erreur lors de la création de la base de données !: " . $error->getMessage() . "<br/>";
    die();
}

try {
    $dbh->exec("USE $DB_NAME");
    echo "... Connected to the new database ... <br/>";
} catch (PDOException $error) {
    print "Error while connecting to the new database !: " . $error->getMessage() . "<br/>";
    die();
}

try {
    $request = "CREATE TABLE users (
    ID INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    NAME VARCHAR(100),
    PASSWORD VARCHAR(128),
    EMAIL VARCHAR (100),
    VALIDATE boolean)";
    $dbh->exec($request);
    echo "... New users table created ... <br/>";
} catch (PDOException $error) {
    print "Error while creating users table !: " . $error->getMessage() . "<br/>";
    die();
}

try {
    $request = "CREATE TABLE pictures (FILE VARCHAR(100))";
    $dbh->exec($request);
    echo "... New pictures table created <br/>";
} catch (PDOException $error) {
    print "Error while creating pictures table !: " . $error->getMessage() . "<br/>";
    die();
}

?>