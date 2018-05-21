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

if(isset($_GET['img_id']) && !empty($_GET['img_id'])) {

  $fichier = $_GET['img_id'];
  $chemin_img = "./photos/$fichier";
  $img_dims   = getimagesize($chemin_img);
  $img_w = $img_dims[0];
  $img_h = $img_dims[1];
}

?>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="style.css">

    <script> const VERSION = ' coDIP 0.02'; </script>
  </head>

  <body>

    <h1> <?php echo $fichier; ?>
    </h1>

    <div id="import-et-retour">
    <a href="./phototeque.php" class="retour"> Retour </a>
    </div>

    <div id="cadre-gallerie">
    <table><tr><th></th><th></th></tr>
    <tr>
    <td>
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

    <td class="vue_infos centrage">

      <p> <strong> sRGB </strong> </p>

      <p> CORRECTION GAMMA </p>

      <p> <strong> NIVEAUX    </strong> </p>

      <div class="dot-small bg-red">   </div>
      <div class="dot-small bg-green"> </div>
      <div class="dot-small bg-blue">  </div>
      <div class="dot-small bg-white"> </div>

      <p> ENTREE </p>
      <input type="range" min="0" max="255" value="0" class="slider" id="niv-ent-lim-basse">
        <span id="val-niv-ent-lim-basse"></span>
      <script>
        var curseur_niv_elb = document.getElementById("niv-ent-lim-basse");
        curseur_niv_elb.oninput = function() {
          const UI_ELB_VAL_MAX = curseur_niv_elb.max;
          gl.uniform1f(u_niv_ent_lim_basse, curseur_niv_elb.value/UI_ELB_VAL_MAX);
          gl.uniform1ui(u_command, LEVEL_INPUT_LOWER_BOUND);
          gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
        }
      </script>

      <input type="range" min="0" max="255" value="255" class="slider b2w-hori" id="niv-ent-lim-haute">
      <script>
        var curseur_niv_elh = document.getElementById("niv-ent-lim-haute");
        curseur_niv_elh.oninput = function() {
          const UI_ELH_VAL_MAX = curseur_niv_elh.max;
          gl.uniform1f(u_niv_ent_lim_haute, curseur_niv_elh.value/UI_ELH_VAL_MAX);
          gl.uniform1ui(u_command, LEVEL_INPUT_UPPER_BOUND);
          gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
        }
      </script>

      <p> SORTIE </p>
      <input type="range" min="0" max="255" value="0" class="slider" id="niv-sort-lim-basse"/>
      <script>
        var curseur_niv_slb = document.getElementById("niv-sort-lim-basse");
        curseur_niv_slb.oninput = function() {
          const UI_SLB_VAL_MAX = curseur_niv_slb.max;
          gl.uniform1f(u_niv_sort_lim_basse, curseur_niv_slb.value/UI_SLB_VAL_MAX);
          gl.uniform1ui(u_command, LEVEL_OUTPUT_LOWER_BOUND);
          gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
        }
      </script>

      <input type="range" min="0" max="255" value="255" class="slider b2w-hori" id="niv-sort-lim-haute"/>
      <script>
        var curseur_niv_slh = document.getElementById("niv-sort-lim-haute");
        curseur_niv_slh.oninput = function() {
          const UI_SLH_VAL_MAX = curseur_niv_slh.max;
          gl.uniform1f(u_niv_sort_lim_haute, curseur_niv_slh.value/UI_SLH_VAL_MAX);
          gl.uniform1ui(u_command, LEVEL_OUTPUT_UPPER_BOUND);
          gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
        }
      </script>
      <p> <strong> SATURATION </strong> </p>
      <input type="range" min="-100" max="100" value="0" class="slider" id="satu"/>
      <script type="text/javascript" src="./edition/ui_saturation.js"/>

      <p> <strong> LUMINOSITE </strong> </p>
      <input type="range" min="-100" max="100" value="0" class="slider" id="lumi"/>
      <script>
        var curseur_lumi = document.getElementById("lumi");
        curseur_lumi.oninput = function() {
          gl.uniform1f(u_lumi_coeff, curseur_lumi.value/100.0f);
          gl.uniform1ui(u_command, INTENSITY_CONTROL);
          gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
        }
      </script>

      <p> <strong> COURBE </strong> </p>

      <div class="dot-small bg-red">   </div>
      <div class="dot-small bg-green"> </div>
      <div class="dot-small bg-blue">  </div>
      <div class="dot-small bg-white"> </div>

      <canvas id="niveaux-courbe"></canvas>
      <script> <?php echo file_get_contents("./edition/ui_levels_curve.js"); ?> </script>

      <p> <strong> HISTOGRAMME </strong> </p>

    </td>
    </tr></table>
    </div>

  </body>
</html>
