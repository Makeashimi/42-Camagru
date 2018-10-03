<?php
if (isset($_GET['page']) && (is_numeric($_GET['page']) && $_GET['page'] > 0)) {
    if (!isset($_GET['page']))
        $choice = 0;
    else
        $choice = ($_GET['page']-1)*9;
    $request = "SELECT link, id FROM `pictures` ORDER BY id DESC LIMIT $choice, 9";
    $images = $pdo->query($request);
    foreach ($images as $image) {
        $officiel = str_replace(' ', '+', $image[0]);
        echo "<img id='picture' alt='$image[1]' onclick='showdialog()' class='picture' src='$officiel'/>";
    }
}
else {
    ?>
    <head>
        <meta http-equiv="refresh" content="0; URL='./index.php?page=1'"/>
    </head>
<?php
}
?>