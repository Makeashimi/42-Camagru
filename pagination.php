<div class="pagination">
<!-- <a href="#">&laquo;</a> -->
<?php
    $request = "SELECT COUNT(*) FROM `pictures` WHERE user_id";
    $nb_images = $pdo->query($request)->fetch()[0];
    $nb_pages = $nb_images/9;
    $index = 1;
    for ($nb_pages; $nb_pages > 0; $nb_pages--) {
        if (isset($_GET['page']) && $_GET['page'] == $index)
            echo "<a class='active' href='index.php?page=$index'>".$index++."</a>";
        else
            echo "<a href='index.php?page=$index'>".$index++."</a>";
    }
?>
<!-- <a href="#">&raquo;</a> -->
</div>