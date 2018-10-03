<link rel="stylesheet" type="text/css" href="./dialogbox.css">

  <div id="hide">
    <div class="popup">
        <div class="comment">
        </div> 
        <?php
          if (isset($_SESSION['user'])) {
            //requete sql pour recuperer l'user_id de l'img.src
            if ($_SESSION['user'] == 'Shimi') {
                //si il est connecté et avec le bon id_user, supprimer
              echo "<div class='remove' onClick='askedDelete()'>Delete the picture ?</div>";
            }
          }
        ?>
    </div>
  </div>
  <script type="text/javascript" src="dialog.js"></script>

<?php
  require_once("config/pdo.php");

  if (!empty($_POST['id'])) {
    $id = $_POST['id'];
    $request = "DELETE FROM `pictures` WHERE id=$id";
    $pdo->exec($request);
  }
?>