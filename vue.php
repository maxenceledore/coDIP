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

if(isset($_GET['img_id']) && !empty($_GET['img_id'])) {
  $fichier = $_GET['img_id'];
}

?>

    <h1> <?php echo $fichier ?> </h1>

    <div id="import-et-retour">
    <a href="./phototeque.php" class="retour"> Retour </a>
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
         $FileSize = '';
         $MimeType = '';
         $Width = '';
         $Height = '';
         $SectionsFound = '';
         $Make = '';
         $Model = '';
         $ExposureTime = '';
         $FNumber = '';
         $ExposureBiasValue = '';
         $FocalLength = '';
         $DateTimeOriginal = '';
         $ColorSpace = '';
         $ISOSpeedRatings='';
         $WhiteBalance = '';

         foreach ($infos_fichier as $key => $section)
            foreach ($section as $name => $val) {
               if($name === 'FileSize') {
                  $val = "File size : ".$val.' Bytes';
                  $FileSize = '<p>'.$val.'</p>';
                  }
               else if($name === 'MimeType') {
                  $val = "MIME type : ".$val;
                  $MimeType = '<p>'.$val.'</p>';
                  }
               else if($name === 'Width' || $name === 'ExifImageWidth') {
                  $val = "Width : ".$val;
                  $Width = '<p>'.$val.'</p>';
                  }
               else if($name === 'Height' || $name === 'ExifImageHeight') {
                  $val = "Height : ".$val;
                  $Height = '<p>'.$val.'</p>';
                  }
               else if($name === 'SectionsFound') {
                  $val = "Sections Found : ".$val;
                  $SectionsFound = '<p>'.$val.'</p>';
                  }
               else if($name === 'Make') {
                  $val = "Make : ".$val;
                  $Make = '<p>'.$val.'</p>';
                  }
               else if($name === 'Model') {
                  $val = "Model : ".$val;
                  $Model = '<p>'.$val.'</p>';
                  }
               else if($name === 'ExposureTime') {
                  $val = "Exposure Time : ".$val;
                  $ExposureTime = '<p>'.$val.'</p>';
                  }
               else if($name === 'FNumber') {
                  $val = "FNumber : ".$val;
                  $FNumber = '<p>'.$val.'</p>';
                  }
               else if($name === 'ExposureBiasValue') {
                  $val = "Exposure Bias Value : ".$val;
                  $ExposureBiasValue = '<p>'.$val.'</p>';
                  }
               else if($name === 'FocalLength') {
                  $val = "Focal Length : ".$val;
                  $FocalLength = '<p>'.$val.'</p>';
                  }
               else if($name === 'DateTimeOriginal') {
                  $val = "Date and Time : ".$val;
                  $DateTimeOriginal = '<p>'.$val.'</p>';
                  }
               else if($name === 'ColorSpace') {
                  if ($val == 1)
                     $val = 'sRGB';
                  $val = "Color Space : ".$val;
                  $ColorSpace = '<p>'.$val.'</p>';
                  }
               else if($name === 'ISOSpeedRatings') {
                  $val = "ISO : ".$val;
                  $ISOSpeedRatings = '<p>'.$val.'</p>';
                  }
               else if($name === 'WhiteBalance') {
                  if($val == 0)
                    $val = 'Automatic';
                  else
                    $val = 'Manual';
                  $val = "White Balance : ".$val;
                  $WhiteBalance = '<p>'.$val.'</p>';
                  }
               }

               echo
               $Width.
               $Height.
               $DateTimeOriginal.
               $FileSize.
               $MimeType.
               $Make.
               $Model.
               $ISOSpeedRatings.
               $ExposureTime.
               $FNumber.
               $ExposureBiasValue.
               $FocalLength.
               $WhiteBalance.
               $ColorSpace.
               $SectionsFound;

               echo "<a href=./index.php?page=edition&img_id=$fichier class=\"bouton\">Editer</a>";
               echo "<a href=\"$chemin_img\" class=\"bouton\" download>Télécharger</a>";

//           foreach ($infos_fichier as $key => $section)
//              foreach ($section as $name => $val)
//                 echo "$key.$name: $val<br />\n";
      }
    ?>
    </td>
    </tr></table>
    </div>
