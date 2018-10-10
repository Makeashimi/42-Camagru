<?php
require_once("config/pdo.php");
session_start();

if (!empty($_POST['picture_id'])) {
    $picture_id = $_POST['picture_id'];
    $ret = "Ko ";

    if (isset($_SESSION['user'])) {
        //requete pour savoir si je peux delete
        $id_user = $_SESSION['id_user'];
        $request = "SELECT user_id FROM `pictures` WHERE id=$picture_id";
        $user_id = $pdo->query($request)->fetch()[0];
        if ($user_id == $id_user)
            $ret = "Ok ";
    }
    $request = "SELECT COUNT(id) FROM `loves` WHERE id=$picture_id";
    $ret = $ret.$pdo->query($request)->fetch()[0];

    $request = "SELECT comments.text, comments.id_comment, users.name FROM comments INNER JOIN users ON comments.id_user = users.id WHERE comments.id_picture='$picture_id'";
    $array = json_encode($pdo->query($request)->fetchAll(PDO::FETCH_ASSOC));

    // $request = "SELECT name FROM `users` WHERE id='$array'";
    echo $ret." ".$array;
}

if (!empty($_POST['like'])) {
    if (!isset($_SESSION['user'])) {
        echo "Fail";
        return ;
    }

    $picture_id = $_POST['like'];
    $user_id = $_SESSION['id_user'];

    //recuperer le nombre de likes sur cette photo
    $request = "SELECT COUNT(id) FROM `loves` WHERE id=$picture_id";
    $nb_likes = $pdo->query($request)->fetch()[0];

    //verifier que le type n'a pas deja liké
    $request = "SELECT COUNT(id) FROM `loves` WHERE id=$picture_id AND user_id=$user_id";
    $likes = $pdo->query($request)->fetch()[0];
    if ($likes > 0) {
        //delete le like
        $request = "DELETE FROM `loves` WHERE id=$picture_id AND user_id=$user_id";
        $pdo->exec($request);
        $nb_likes = intval($nb_likes) - 1;
        echo $nb_likes;
        return ;
    }

    //inserer le meme id et le user_name avec
    $request = "INSERT INTO `loves` (id, user_id) VALUES ($picture_id, $user_id)";
    $pdo->exec($request);
    $nb_likes = intval($nb_likes) + 1;
    echo $nb_likes;
}

if (!empty($_POST['id']) && !empty($_POST['comment'])) {
    $picture_id = $_POST['id'];
    $user_id = $_SESSION['id_user'];
    $comment = $_POST['comment'];

    $request = "INSERT INTO `comments` (id_picture, text, id_user) VALUES ($picture_id, '$comment', $user_id)";
    $pdo->exec($request);
    $request = "SELECT id_comment, id_user FROM `comments` WHERE id_user='$user_id' ORDER BY id_comment DESC LIMIT 1";
    $ret = $pdo->query($request)->fetch();
    $request = "SELECT name FROM `users` WHERE id='$ret[1]'";
    $name = $pdo->query($request)->fetch()[0];
    echo $ret[0]." ".$name;
}

if (!empty($_POST['delete_comment'])) {
    $text = $_POST['delete_comment'];
    $request = "SELECT id_user FROM `comments` WHERE id_comment='$text'";
    $id_user = $pdo->query($request)->fetch()[0];

    if (empty($_SESSION['user']) || $id_user != $_SESSION['id_user'])
      echo "Fail";
    else {
      $request = "DELETE FROM `comments` WHERE id_comment='$text'";
      $pdo->exec($request);
    }
}
?>