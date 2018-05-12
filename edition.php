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

    <script> var VERSION = '0.01'; </script>
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
        var shader = gl.createShader(type);
        gl.shaderSource(shader, source);
        gl.compileShader(shader);
        return shader;
    }

    window.createProgram = function(gl, vsSrc, fsSrc) {
        var program = gl.createProgram();
        var vshader = createShader(gl, vsSrc, gl.VERTEX_SHADER);
        var fshader = createShader(gl, fsSrc, gl.FRAGMENT_SHADER);

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

        var gl = canvasRenduOpengl.getContext('webgl2', { antialias: false });
        var isWebGL2 = !!gl;
        if(!isWebGL2) {
            window.alert(`
            Gallerie requiert WebGL 2 (OpenGL ES 3)\n
            Les navigateurs suivants supportent WebGL 2 :\n
            - Firefox 51\n
            - Chrome 46\n
            - Safari 11 (activation manuelle)
            `);
            // return;
        }
    (function () {
        var debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
        if(debugInfo) {
          var vendor    = gl.getParameter(debugInfo.UNMASKED_VENDOR_WEBGL);
          var renderer  = gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL);
          /* WebGL/OpenGL version/vend */
          console.log(vendor, renderer);
          }
        var maxTextureSize      = gl.getParameter(gl.MAX_TEXTURE_SIZE);
        var maxArrayTextureLayers = gl.getParameter(gl.MAX_ARRAY_TEXTURE_LAYERS);
        var max3DTextureSize    = gl.getParameter(gl.MAX_3D_TEXTURE_SIZE);
        var maxRenderbufferSize = gl.getParameter(gl.MAX_RENDERBUFFER_SIZE);
        var maxDrawBuffers      = gl.getParameter(gl.MAX_DRAW_BUFFERS);
        var maxUniformBlockSize = gl.getParameter(gl.MAX_UNIFORM_BLOCK_SIZE);
        var maxFragmentUniformVectors = gl.getParameter(gl.MAX_FRAGMENT_UNIFORM_VECTORS);
        var maxFragmentUniformBlocks = gl.getParameter(gl.MAX_FRAGMENT_UNIFORM_BLOCKS);
        var maxCombinedFragmentUniformComponents = gl.getParameter(gl.MAX_COMBINED_FRAGMENT_UNIFORM_COMPONENTS);

        console.log('maxTextureSize:', maxTextureSize,'\n');
        console.log('maxArrayTextureLayers :', maxArrayTextureLayers);
        console.log('max3DTextureSize :', max3DTextureSize,'\n');
        console.log('maxRenderbufferSize :', maxRenderbufferSize,'\n');
        console.log('maxDrawBuffers :', maxDrawBuffers,'\n');
        console.log('maxFragmentUniformVectors :', maxFragmentUniformVectors,'\n');
        console.log('maxFragmentUniformBlocks :', maxFragmentUniformBlocks,'\n');
        console.log('maxUniformBlockSize :', maxUniformBlockSize,'\n');
        console.log('maxCombinedFragmentUniformComponents :', maxCombinedFragmentUniformComponents,'\n');
    })();
        <?php
          $img_dims = getimagesize($chemin_img);
          echo 'var img_w ='.$img_w.";\n";
          echo 'var img_h ='.$img_h.";\n";
        ?>

        var program = createProgram(gl, getShaderSource('vs'), getShaderSource('fs'));
        var imgtext_loc  = gl.getUniformLocation(program, 'img');
        var u_lumi_coeff = gl.getUniformLocation(program, "lumi_coeff");
        var u_satu_coeff = gl.getUniformLocation(program, "satu_coeff");
        var u_niv_ent_lim_basse  = gl.getUniformLocation(program, "niv_ent_lim_basse");
        var u_niv_ent_lim_haute  = gl.getUniformLocation(program, "niv_ent_lim_haute");
        var u_niv_sort_lim_basse = gl.getUniformLocation(program, "niv_sort_lim_basse");
        var u_niv_sort_lim_haute = gl.getUniformLocation(program, "niv_sort_lim_haute");

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

        var vertexPosLocation = 0;
        var vertices = new Float32Array([
            -1.0, -1.0,
             1.0, -1.0,
            -1.0,  1.0,
             1.0,  1.0
        ]);

        var texCoordsLocation = 1;

        var texCoords = new Float32Array([
             0.0, 1.0,
             1.0, 1.0,
             0.0, 0.0,
             1.0, 0.0
        ]);

//         var texCoords = new Float32Array([
//              1.0, 0.0,
//              1.0, 1.0,
//              0.0, 0.0,
//              0.0, 1.0
//         ]);

        var vertexArray = gl.createVertexArray();
        gl.bindVertexArray(vertexArray);

        var vertexPosBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, vertexPosBuffer);
        gl.bufferData(gl.ARRAY_BUFFER, vertices, gl.STATIC_DRAW);
        gl.enableVertexAttribArray(vertexPosLocation);
        gl.vertexAttribPointer(vertexPosLocation, 2, gl.FLOAT, false, 0, 0);

        var texCoordBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, texCoordBuffer);
        gl.bufferData(gl.ARRAY_BUFFER, texCoords, gl.STATIC_DRAW);
        gl.enableVertexAttribArray(texCoordsLocation);
        gl.vertexAttribPointer(texCoordsLocation, 2, gl.FLOAT, false, 0, 0);


        gl.bindVertexArray(null);
        gl.bindVertexArray(vertexArray);

        const image = new Image();

        image.onload = function() {
            var texture = gl.createTexture();
            gl.activeTexture(gl.TEXTURE0);
            gl.bindTexture(gl.TEXTURE_2D, texture);
            gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGB, img_w, img_h, 0, gl.RGB, gl.UNSIGNED_BYTE, image);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);

            var imgtext_loc = gl.getUniformLocation(program, 'img');
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

      <p> ENTREE </p>
      <div class="slidecontainer">
        <input type="range" min="0" max="255" value="0" class="slider" id="niv-ent-lim-basse">
        <span id="val-niv-ent-lim-basse"></span>
      </div>
      <script>
        var curseur_niv_elb = document.getElementById("niv-ent-lim-basse");
        curseur_niv_elb.oninput = function() {
          console.log(curseur_niv_elb.value);
        }
      </script>

      <div class="slidecontainer">
        <input type="range" min="0" max="255" value="255" class="slider" id="niv-ent-lim-haute">
      </div>
      <script>
        var curseur_niv_elh = document.getElementById("niv-ent-lim-haute");
        curseur_niv_elh.oninput = function() {
          console.log(curseur_niv_elh.value);
        }
      </script>

      <p> SORTIE </p>
      <div class="slidecontainer">
        <input type="range" min="0" max="255" value="0" class="slider" id="niv-sort-lim-basse"/>
      </div>
      <script>
        var curseur_niv_slb = document.getElementById("niv-sort-lim-basse");
        curseur_niv_slb.oninput = function() {
          console.log(curseur_niv_slb.value);
        }
      </script>

      <div class="slidecontainer">
        <input type="range" min="0" max="255" value="255" class="slider" id="niv-sort-lim-haute"/>
      </div>
      <script>
        var curseur_niv_slh = document.getElementById("niv-sort-lim-haute");
        curseur_niv_slh.oninput = function() {
          console.log(curseur_niv_slh.value);
        }
      </script>

      <p> <strong> SATURATION </strong> </p>
      <div class="slidecontainer">
        <input type="range" min="-100" max="100" value="0" class="slider" id="satu"/>
      </div>
      <script>
        var curseur_satu = document.getElementById("satu");
        curseur_satu.oninput = function() {
          const COEFF_INTERNAL_MIN = 0.0;
          const COEFF_INTERNAL_MAX = 2.0;
          const UI_SATU_VAL_MIN = curseur_satu.min;
          const UI_SATU_VAL_MAX = curseur_satu.max;
          const m = (COEFF_INTERNAL_MAX-COEFF_INTERNAL_MIN)/(UI_SATU_VAL_MAX-UI_SATU_VAL_MIN);
          const p = COEFF_INTERNAL_MAX - m*UI_SATU_VAL_MAX;
          const coeff = m*curseur_satu.value+p;
          gl.uniform1f(u_satu_coeff, coeff);
          gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
       // console.log(coeff);
        }
      </script>

      <p> <strong> LUMINOSITE </strong> </p>
      <div class="slidecontainer">
        <input type="range" min="-100" max="100" value="0" class="slider" id="lumi"/>
      </div>
      <script>
        var curseur_lumi = document.getElementById("lumi");
        curseur_lumi.oninput = function() {
          gl.uniform1f(u_lumi_coeff, curseur_lumi.value);
          gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
        }
      </script>

      <p> <strong> COURBE </strong> </p>

      <canvas id="niveaux-courbe"></canvas>
      <script type="text/javascript" src="./edition/ui_levels_curve.js"></script>

      <p> <strong> HISTOGRAMME </strong> </p>

    </td>
    </tr></table>
    </div>

  </body>
</html>
