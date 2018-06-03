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
  $chemin_img = "./photos/$fichier";
  $img_dims   = getimagesize($chemin_img);
  $img_w = $img_dims[0];
  $img_h = $img_dims[1];
}

?>
    <?php echo file_get_contents('main-cat.htm'); ?>
    <?php echo file_get_contents('menu-edit.htm'); ?>

    <div id="cadre-gallerie">
    <table><tr><th></th><th></th></tr>
    <tr>
    <td>
      <p class="centrage gras"> <?php echo $fichier; ?></p>
      <canvas id="gl-edit-render"
        <?php echo "width=\"$img_w\" height=\"$img_h\""; ?> >
      </canvas>

    <script id="vs" type="application/x-glsl">
<?php echo file_get_contents("./edition/vs-image-geometry.glsl") ?>
    </script>

    <script id="fs" type="application/x-glsl">
<?php echo file_get_contents("./edition/fs-image-color-control.glsl") ?>
    </script>

    <script>
    <?php
      echo 'const img_w    ='.$img_w.";\n";
      echo 'const img_h    ='.$img_h.";\n";
      echo 'const img_path ='."\"$chemin_img\"".";\n";
    ?>
    </script>

    <script> <?php echo file_get_contents("./edition/ui_edition-renderer.js"); ?> </script>

    <script>
<?php echo file_get_contents("./edition/codip_states.js") ?>
    </script>

    <script>
<?php echo file_get_contents("./edition/ui_events_util.js") ?>
    </script>

    </td>

    <td class="editing-modules-container centrage">


      <?php
        include('./edition/ui_histogram.php');
        include('./edition/ui_levels.php');
        include('./edition/ui_levels_curve.php');
        include('./edition/ui_luminosity.php');
        include('./edition/ui_saturation.php');
      ?>

    </td>
    </tr></table>
    </div>
