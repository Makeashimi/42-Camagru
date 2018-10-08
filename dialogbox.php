<link rel="stylesheet" type="text/css" href="./dialogbox.css">

  <div id="hide" size="auto">
    <div id="popup">
        <img id='image_gallery' src='' size="auto">
        <div onClick='askedLike()'>Like : </div>
        <!-- div like here -->
        <!-- <div> -->
        <!-- <form id="comment" action="dialogbox.php" method="post"> -->
          <?php
            if (isset($_SESSION['user'])) {
              echo "<input type='text' id='comment' name='comments'>";
              echo "<input type='button' onClick='askedComment()' value='Validate'>";
            }
          ?>
        <!-- </form> -->
        <!-- </div> -->
        <!-- div remove create here-->
        <div id="null"></div>
    </div>
  </div>
  <script type="text/javascript" src="dialog.js"></script>

<?php
  require_once("config/pdo.php");

  if (!empty($_POST['id'])) {
    $id = $_POST['id'];
    $request = "DELETE FROM `pictures` WHERE id=$id";
    $pdo->exec($request);
    $request = "DELETE FROM `loves` WHERE id=$id";
    $pdo->exec($request);
  }

  if (!empty($_POST['id']) && !empty($_POST['delete_comment'])) {
    $text = $_POST['delete_comment'];
    $id = $_POST['id'];
    $request = "DELETE FROM `comments` WHERE text=$text AND id=$id";
    $pdo->exec($request);
  }

?>