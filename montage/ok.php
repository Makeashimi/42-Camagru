<?php
// header ("Content-type: image/jpeg"); // L'image que l'on va créer est un jpeg

// // On charge d'abord les images
// $source = imagecreatefrompng("images/portal.png"); // Le logo est la source
// $destination = imagecreatefromjpeg("images/flag.jpg"); // La photo est la destination

// // Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
// $largeur_source = imagesx($source);
// $hauteur_source = imagesy($source);
// $largeur_destination = imagesx($destination);
// $hauteur_destination = imagesy($destination);

// // On veut placer le logo en bas à droite, on calcule les coordonnées où on doit placer le logo sur la photo
// $destination_x = $largeur_destination - $largeur_source;
// $destination_y =  $hauteur_destination - $hauteur_source;

// // On met le logo (source) dans l'image de destination (la photo)
// imagecopymerge($destination, $source, $destination_x, $destination_y, 0, 0, $largeur_source, $hauteur_source, 40);

// // On affiche l'image de destination qui a été fusionnée avec le logo
// imagejpeg($destination);

$file = 'images/flag.jpg' ; # L'emplacement de l'image à redimensionner. L'image peut être de type jpeg, gif ou png
$x = 125;
$y = 100; # Taille en pixel de l'image redimensionné
$size = getimagesize($file);

if ( $size) {
    echo 'Image en cours de redimensionnement...';
    if ($size['mime']=='image/jpeg' ) {
        $img_big = imagecreatefromjpeg($file); # On ouvre l'image d'origine
        $img_new = imagecreate($x, $y);

        # création de la miniature
        // $img_mini = imagecreatetruecolor($x, $y)
        // $img_mini = imagecreate($x, $y);

        // copie de l'image, avec le redimensionnement.
        imagecopyresized($img_new,$img_big,0,0,0,0,$x,$y,$size[0],$size[1]);
        imagejpeg($img_new,$file );
    }
    elseif ($size['mime']=='image/png' ) {
        $img_big = imagecreatefrompng($file); # On ouvre l'image d'origine
        $img_new = imagecreate($x, $y);

        # création de la miniature
        // $img_mini = imagecreatetruecolor($x, $y)
        // $img_mini = imagecreate($x, $y);

        // copie de l'image, avec le redimensionnement.
        imagecopyresized($img_new,$img_big,0,0,0,0,$x,$y,$size[0],$size[1]);
        imagepng($img_new,$file );
    }
    echo 'Image redimensionnée !';
}

?>