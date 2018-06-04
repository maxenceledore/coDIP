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

      <?php include('./edition/codip_states.php'); ?>
      <?php include('./edition/ui_events_util.php'); ?>
      <?php include('./edition/vs-image-geometry.php'); ?>
      <?php include('./edition/fs-image-color-control.php'); ?>

      <canvas id="gl-edit-render" onclick="get_pixel_click_coords(event)"></canvas>
      <script>
      document.getElementById("gl-edit-render").width = img_w;
      document.getElementById("gl-edit-render").height = img_h;
      </script>

      <?php include('./edition/ui_edition-renderer.php'); ?>

    </td>

    <td class="editing-modules-container centrage">


      <?php
        include('./edition/ui_color-balance.php');
        include('./edition/ui_crop.php');
        include('./edition/ui_gaussian-blur.php');
        include('./edition/ui_histogram.php');
        include('./edition/ui_layers.php');
        include('./edition/ui_levels.php');
        include('./edition/ui_levels_curve.php');
        include('./edition/ui_luminosity.php');
        include('./edition/ui_negate.php');
        include('./edition/ui_rotate.php');
        include('./edition/ui_saturation.php');
        include('./edition/ui_scale.php');
        include('./edition/ui_zoom.php');
      ?>

    </td>
    </tr></table>
    </div>
