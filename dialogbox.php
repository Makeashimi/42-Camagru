<link rel="stylesheet" type="text/css" href="./dialogbox.css">

  <div id="hide" size="auto">
    <div id="popup">
        <img id='image_gallery' src='' size="auto"><br/>
        <div id="like" class="fas fa-thumbs-up" onClick='askedLike()'></div>
        <div id="comment_body">
          <div id="null42"></div>
        </div>
          <?php
            if (isset($_SESSION['user'])) {
              echo "<textarea id='comment' name='comments' maxlength='500' placeholder='Max 500 char'></textarea><br/>";
              echo "<input class='button_validate' type='button' onClick='askedComment()' value='Validate'>";
            }
          ?>
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
    $request = "DELETE FROM `comments` WHERE id_picture=$id";
    $pdo->exec($request);
    $request = "DELETE FROM `loves` WHERE id=$id";
    $pdo->exec($request);
  }

?>