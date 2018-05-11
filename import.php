<?php

define("CONTRAINTE_H", 150);

if(isset($_FILES['fichier']) && !empty($_FILES['fichier'])) {

   if($_FILES['fichier']['error'] != 0) {
      unset($chemin_upload);
      header('Location: index.php');
      }

   $nom = substr(md5($_FILES['fichier']['tmp_name']),0,12);
   $nom .= '.jpg';

   $chemin_img = "./photos/$nom";

   $xfert_succes =
      move_uploaded_file($_FILES['fichier']['tmp_name'], $chemin_img);

    $infos_fichier = exif_read_data($chemin_img, 'FILE',true);
      if($infos_fichier !== false) {
         foreach ($infos_fichier as $key => $section)
            foreach ($section as $name => $val) {
               if($name === 'Orientation') {
                  $orientation = $val;
                  switch($orientation) {
                     case 3:
                        $img = imagecreatefromjpeg($chemin_img);
                        $img = imagerotate($img,180,0);
                        imageinterlace($img, true);
                        imagejpeg($img, $chemin_img, 90);
                        imagedestroy($img);
                        break;
                     case 6:
                         $img = imagecreatefromjpeg($chemin_img);
                         $img = imagerotate($img,-90,0);
                         imageinterlace($img, true);
                         imagejpeg($img, $chemin_img, 90);
                         imagedestroy($img);
                        break;
                     case 8:
                        $img = imagecreatefromjpeg($chemin_img);
                        $img = imagerotate($img,90,0);
                        imageinterlace($img, true);
                        imagejpeg($img, $chemin_img, 90);
                        imagedestroy($img);
                        break;
                     case 6:
                     default:
                        break;
                     }
                  }
               }
      }

   $img_dims = getimagesize($chemin_img);

   $longueur = $img_dims[0];
   $hauteur = $img_dims[1];

   if($hauteur != CONTRAINTE_H) {
      $longueur_minia = intval($longueur * (CONTRAINTE_H/$hauteur));
      $hauteur_minia  = CONTRAINTE_H;
      }

   $chemin_img_minia = "./min/$nom";

   $img = imagecreatefromjpeg($chemin_img);
   $minia = imagecreatetruecolor($longueur_minia, $hauteur_minia);
   imagecopyresampled($minia, $img, 0, 0, 0, 0,
                    $longueur_minia, $hauteur_minia, $longueur, $hauteur);

   imageinterlace($minia, true);
   imagejpeg($minia, $chemin_img_minia, 60);
   imagedestroy($img);
   imagedestroy($minia);
}

header('Location: index.php');

?>
