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
    <a href="./index.php" class="retour"> Retour </a>
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

    (function () {
    'use strict';

    function createShader(gl, source, type) {
        const shader = gl.createShader(type);
        gl.shaderSource(shader, source);
        gl.compileShader(shader);
        return shader;
    }

    window.createProgram = function(gl, vsSrc, fsSrc) {
        const program = gl.createProgram();
        const vshader = createShader(gl, vsSrc, gl.VERTEX_SHADER);
        const fshader = createShader(gl, fsSrc, gl.FRAGMENT_SHADER);

        if(!gl.getShaderParameter(vshader, gl.COMPILE_STATUS))
           console.log(gl.getShaderInfoLog(vshader));

        if(!gl.getShaderParameter(fshader, gl.COMPILE_STATUS))
           console.log(gl.getShaderInfoLog(fshader));

        var log = gl.getShaderInfoLog(vshader);
        if(log)
           console.log(log);

        log = gl.getShaderInfoLog(fshader);
        if(log)
           console.log(log);

        log = gl.getProgramInfoLog(program);
        if(log)
           console.log(log);

        gl.attachShader(program, vshader);
        gl.attachShader(program, fshader);
        gl.deleteShader(vshader);
        gl.deleteShader(fshader);
        gl.linkProgram(program);

        return program;
    };

    window.getShaderSource = function(id) {
        return document.getElementById(id).textContent.replace(/^\s+|\s+$/g, '');
    };

})();

    </script>

    <script>
            'use strict';
        const canvasRenduOpengl = document.querySelector("#gl-edit-render");

        const gl = canvasRenduOpengl.getContext('webgl2', { antialias: false });
        const isWebGL2 = !!gl;
        if(!isWebGL2) {
            window.alert(`
            Gallerie requiert WebGL 2 (OpenGL ES 3)\n
            Les navigateurs suivants supportent WebGL 2 :\n
            - Firefox 51\n
            - Chrome 46\n
            - Safari 11 (activation manuelle)
            `);
        }

        <?php
          $img_dims = getimagesize($chemin_img);
          echo 'const img_w ='.$img_w.";\n";
          echo 'const img_h ='.$img_h.";\n";
        ?>

        const NO_OP                    =  0;
        const SATURATION_CONTROL       =  1;
        const INTENSITY_CONTROL        =  2;
        const LEVEL_INPUT_LOWER_BOUND  =  3;
        const LEVEL_INPUT_UPPER_BOUND  =  4;
        const LEVEL_OUTPUT_LOWER_BOUND =  5;
        const LEVEL_OUTPUT_UPPER_BOUND =  6;

        var command = NO_OP;
        var program = createProgram(gl, getShaderSource('vs'), getShaderSource('fs'));
        var imgtext_loc  = gl.getUniformLocation(program, 'img');
        var u_lumi_coeff = gl.getUniformLocation(program, "lumi_coeff");
        var u_satu_coeff = gl.getUniformLocation(program, "satu_coeff");
        var u_niv_ent_lim_basse  = gl.getUniformLocation(program, "niv_ent_lim_basse");
        var u_niv_ent_lim_haute  = gl.getUniformLocation(program, "niv_ent_lim_haute");
        var u_niv_sort_lim_basse = gl.getUniformLocation(program, "niv_sort_lim_basse");
        var u_niv_sort_lim_haute = gl.getUniformLocation(program, "niv_sort_lim_haute");
        var u_command = gl.getUniformLocation(program, "command");

        gl.useProgram(program);
        gl.uniform1f(u_lumi_coeff, 0.0);  /* -100/0/+100 --> -1.0/0.0/+1.0 */
        gl.uniform1f(u_satu_coeff, 1.0);  /* -100/0/+100 --> 0.0/+1.0/+2.0 */
        gl.uniform1f(u_niv_ent_lim_basse,    0./255.);
        gl.uniform1f(u_niv_ent_lim_haute,  255./255.);
        gl.uniform1f(u_niv_sort_lim_basse,   0./255.);
        gl.uniform1f(u_niv_sort_lim_haute, 255./255.);
//      gl.uniform1i(u_lumi_courbe_niveau);
//      gl.uniform1i(u_rouge_courbe_niveau);
//      gl.uniform1i(u_vert_courbe_niveau);
//      gl.uniform1i(u_bleu_courbe_niveau);
        gl.uniform1ui(u_command, NO_OP);

        const vertexPosLocation = 0;
        const vertices = new Float32Array([
            -1.0, -1.0,
             1.0, -1.0,
            -1.0,  1.0,
             1.0,  1.0
        ]);

        const texCoordsLocation = 1;

        const texCoords = new Float32Array([
             0.0, 1.0,
             1.0, 1.0,
             0.0, 0.0,
             1.0, 0.0
        ]);

        const vertexArray = gl.createVertexArray();
        gl.bindVertexArray(vertexArray);

        const vertexPosBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, vertexPosBuffer);
        gl.bufferData(gl.ARRAY_BUFFER, vertices, gl.STATIC_DRAW);
        gl.enableVertexAttribArray(vertexPosLocation);
        gl.vertexAttribPointer(vertexPosLocation, 2, gl.FLOAT, false, 0, 0);

        const texCoordBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, texCoordBuffer);
        gl.bufferData(gl.ARRAY_BUFFER, texCoords, gl.STATIC_DRAW);
        gl.enableVertexAttribArray(texCoordsLocation);
        gl.vertexAttribPointer(texCoordsLocation, 2, gl.FLOAT, false, 0, 0);


        gl.bindVertexArray(null);
        gl.bindVertexArray(vertexArray);

        const image = new Image();

        image.onload = function() {
            const texture = gl.createTexture();
            gl.activeTexture(gl.TEXTURE0);
            gl.bindTexture(gl.TEXTURE_2D, texture);
            gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGB, img_w, img_h, 0, gl.RGB, gl.UNSIGNED_BYTE, image);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);

            const imgtext_loc = gl.getUniformLocation(program, 'img');
            gl.uniform1i(imgtext_loc, 0);

            gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
            };
        image.src = '<?php echo $chemin_img; ?>';

        gl.clearColor(0.1, 0.1, 0.1, 1.0);
        gl.clear(gl.COLOR_BUFFER_BIT);
    </script>

    </td>

    <td class="vue_infos">

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
