<?php

if(isset($_GET['img_id']) && !empty($_GET['img_id'])) {
  $fichier = $_GET['img_id'];
}

?>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>

  <body>

    <h1> <?php echo $fichier ?> </h1>

    <div id="import-et-retour">
    <a href="./index.php" class="retour"> Retour </a>
    </div>

    <div id="cadre-gallerie">
    <table><tr><th></th><th></th></tr>
    <tr>
    <td>
    
    <?php 
      $chemin_img = "./photos/$fichier";
      echo "<img class=\"vue\" alt=\"$fichier\" src=\"$chemin_img\"/>";
    ?>

    </td>

    <td class="vue_infos">
    <?php
      $infos_fichier = exif_read_data($chemin_img, 'FILE',true);

      if($infos_fichier !== false) {
         foreach ($infos_fichier as $key => $section)
            foreach ($section as $name => $val) {
               if($name === 'FileSize')
                  echo "<p> Taille : $val octets </p>\n";
               else if($name === 'MimeType')
                  echo "<p> Type : $val </p>\n";
               else if($name === 'Width')
                  echo "<p> Largeur : $val </p>\n";
               else if($name === 'Height')
                  echo "<p> Hauteur : $val </p>\n";
               else if($name === 'SectionsFound')
                  echo "<p> Sources EXIF : $val </p>\n";
               else if($name === 'Make')
                  echo "<p> Appareil : $val </p>\n";
               else if($name === 'Model')
                  echo "<p> Modèle : $val </p>\n";
               else if($name === 'ExposureTime')
                  echo "<p> Durée d'exposition : $val </p>\n";
               else if($name === 'FNumber')
                  echo "<p> Ouverture : $val </p>\n";
               else if($name === 'ExposureBiasValue')
                  echo "<p> Correction d\'exposition : $val </p>\n";
               else if($name === 'FocalLength')
                  echo "<p> Longueur de focale : $val </p>\n";
               else if($name === 'DateTimeOriginal')
                  echo "<p> Date et heure de prise de vue : $val </p>\n";
               else if($name === 'ColorSpace')
                  echo "<p> Espace couleurs : $val </p>\n";
               }
               echo "<a href=./edition.php?img_id=$fichier class=\"bouton\">Editer</a>";
               echo "<a href=\"$chemin_img\" class=\"bouton\" download>Télécharger</a>";

//          foreach ($infos_fichier as $key => $section)
//             foreach ($section as $name => $val)
//                echo "$key.$name: $val<br />\n";
      }
    ?>
    </td>
    </tr></table>
    </div>

  </body>
</html>
