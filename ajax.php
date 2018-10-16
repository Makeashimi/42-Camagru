<?php
require_once("config/pdo.php");
session_start();

if (!empty($_POST['picture_id'])) {
    $picture_id = $_POST['picture_id'];
    $ret = "Ko";
    $request = "SELECT pictures.user_id, pictures.link, users.name FROM pictures INNER JOIN users ON pictures.user_id = users.id WHERE pictures.id='$picture_id'";
    $pictures_infos = $pdo->query($request)->fetch();
    $user_id = $pictures_infos[0];
    $src = str_replace(' ', '+', $pictures_infos[1]);
    $name = $pictures_infos[2];
    $email = null;

    if (isset($_SESSION['id_user'])) {
        $id_user = $_SESSION['id_user'];
        $request = "SELECT email FROM `users` WHERE id='$user_id'";
        $email = $pdo->query($request)->fetch()[0];
        if ($user_id == $id_user)
            $ret = "Ok";
    }
    $request = "SELECT COUNT(id) FROM `loves` WHERE id=$picture_id";
    $like = $pdo->query($request)->fetch()[0];

    $session_id = $_SESSION['id_user'];
    $request = "SELECT COUNT(id) FROM `loves` WHERE id=$picture_id AND user_id=$session_id";
    $user_like = $pdo->query($request)->fetch()[0];
    if ($user_like > 0)
        $color = "#3eb489";
    else
        $color = "black";

    $request = "SELECT comments.text, comments.id_comment, users.name FROM comments INNER JOIN users ON comments.id_user = users.id WHERE comments.id_picture='$picture_id'";
    $array = $pdo->query($request)->fetchAll(PDO::FETCH_ASSOC);
    for ($i = 0; $i < count($array); $i++) {
        $array[$i]["text"] = str_replace(' ', '+', $array[$i]['text']);
    }

    $tablo = array();
    $tablo["ret"] = $ret;
    $tablo["like"] = $like;
    $tablo["array"] = $array;
    $tablo["name"] = $name;
    $tablo["email"] = $email;
    $tablo["src"] = $src;
    $tablo["color"] = $color;
    echo json_encode($tablo);
}

if (!empty($_POST['like'])) {
    if (!isset($_SESSION['user'])) {
        echo "Fail";
        return ;
    }

    $picture_id = $_POST['like'];
    $user_id = $_SESSION['id_user'];

    $request = "SELECT COUNT(id) FROM `loves` WHERE id=$picture_id";
    $nb_likes = $pdo->query($request)->fetch()[0];

    $request = "SELECT COUNT(id) FROM `loves` WHERE id=$picture_id AND user_id=$user_id";
    $likes = $pdo->query($request)->fetch()[0];
    if ($likes > 0) {
        $request = "DELETE FROM `loves` WHERE id=$picture_id AND user_id=$user_id";
        $pdo->exec($request);
        $nb_likes = intval($nb_likes) - 1;
        echo $nb_likes." black";
        return ;
    }

    $request = "INSERT INTO `loves` (id, user_id) VALUES ($picture_id, $user_id)";
    $pdo->exec($request);
    $nb_likes = intval($nb_likes) + 1;
    echo $nb_likes." #3eb489";
}

if (!empty($_POST['id']) && !empty($_POST['comment']) && !empty($_POST['email']) && !empty($_POST['name'])) {
    $picture_id = $_POST['id'];
    if (!isset($_SESSION['id_user'])) {
        echo "Fail";
        return ;
    }
    $user_id = $_SESSION['id_user'];
    $comment = $_POST['comment'];

    $request = "INSERT INTO `comments` (id_picture, text, id_user) VALUES ($picture_id, '$comment', $user_id)";
    $pdo->exec($request);
    $request = "SELECT id_comment, id_user FROM `comments` WHERE id_user='$user_id' ORDER BY id_comment DESC LIMIT 1";
    $ret = $pdo->query($request)->fetch();
    $request = "SELECT name, notif FROM `users` WHERE id='$ret[1]'";
    $array = $pdo->query($request)->fetch();
    $name = $array[0];

    if ($array[1] == '0') {
        $page = $_POST['page'];
        $lien = "http://localhost:8080/Camagru/git/index.php?page=".$page."&id=".$picture_id;
        $message = "Welcome back ".$_POST['name'].
        " !\r\n\nIt appears you received a new comment in your picture, check the link below to see this :\r\n".$lien." ! \r\n\nSee you soon :D";
        $subject = "New comment in your picture";
        $to = $_POST['email'];
        if (mail($to, $subject, utf8_decode($message))) {
            $tablo['id_comment'] = $ret[0];
            $tablo['name'] = $name;
            echo json_encode($tablo);
        }
        else
            echo json_encode("Fail");
        return ;
    }
    $tablo['id_comment'] = $ret[0];
    $tablo['name'] = $name;
    echo json_encode($tablo);
}

if (!empty($_POST['delete_comment'])) {
    $text = $_POST['delete_comment'];
    $request = "SELECT id_user FROM `comments` WHERE id_comment='$text'";
    $id_user = $pdo->query($request)->fetch()[0];

    if (!isset($_SESSION['id_user']) || $id_user != $_SESSION['id_user'])
      echo "Fail";
    else {
      $request = "DELETE FROM `comments` WHERE id_comment='$text'";
      $pdo->exec($request);
    }
}

if (!empty($_POST['delete_picture'])) {
    $id = $_POST['delete_picture'];
    $request = "SELECT user_id FROM `pictures` WHERE id='$id'";
    $id_user = $pdo->query($request)->fetch()[0];

    if (!isset($_SESSION['id_user']) || $id_user != $_SESSION['id_user'])
        echo "Fail";
    else {
        $request = "DELETE FROM `pictures` WHERE id=$id";
        $pdo->exec($request);
        $request = "DELETE FROM `comments` WHERE id_picture=$id";
        $pdo->exec($request);
        $request = "DELETE FROM `loves` WHERE id=$id";
        $pdo->exec($request);
    }
}
?>