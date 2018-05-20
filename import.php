<?php
//    Copyright (C) 2018  Maxence Le Doré (maxence.ledore@gmail.com)

//    This file is part of coDIP.

//    coDIP is free software: you can redistribute it and/or modify
//    it under the terms of the GNU Affero General Public License as
//    published by the Free Software Foundation, either version 3 of 
//    the License, or (at your option) any later version.

//    coDIP is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU Affero General Public License for more details.

//    You should have received a copy of the GNU Affero General Public
//    License along with coDIP.
//    If not, see <http://www.gnu.org/licenses/>.
?>

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
