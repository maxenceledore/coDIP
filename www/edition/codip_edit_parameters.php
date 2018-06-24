<script>

<?php
  echo 'const img_w    ='.$img_w.";\n";
  echo 'const img_h    ='.$img_h.";\n";
  echo 'const img_path ='."\"$chemin_img\"".";\n";
?>

const VERSION = 'coDIP 0.05';
var WebGLversion = 0;
var ESSL_version_string = "#version ";

screen.orientation.lock('landscape');

var canvasRenduOpengl = null;

var clicked_pix_x = 0;
var clicked_pix_y = 0;

var imgGeomTilingVAO = null;

var num_layers = 1;
var layer0 = null;

var previewlayerFB        = null;
var render2layerFB        = null;
var previewlayerFBtexture = null;

var zoom   = 1.0;
var offset = 0.0;
var flip_y = 1.0;
var rotation_mat = new Float32Array([1.0,0.0,0.0,1.0]);

var histo_blue    = new Float32Array(255);
var histo_green   = new Float32Array(255);
var histo_red     = new Float32Array(255);
var histo_cyan    = new Float32Array(255);
var histo_yellow  = new Float32Array(255);
var histo_magenta = new Float32Array(255);

var jpeg_quality;

var program = null;
var u_command  = null;
var u_scale = null;
var u_flip_y = null;
var u_planar_rotation = null;
var imgtext_loc   = null;
var u_lumi_coeff  = null;
var u_satu_coeff  = null;
var u_niv_ent_lim_basse   = null;
var u_niv_ent_lim_haute   = null;
var u_niv_sort_lim_basse  = null;
var u_niv_sort_lim_haute  = null;
var u_cb_gm_coeff  = null;
var u_cb_by_coeff  = null;
var u_cb_rc_coeff  = null;

for(var i=0 ; i < 255 ; i++) {
    histo_blue[i] = 0;
    histo_green[i] = 0;
    histo_red[i] = 0;
    histo_cyan[i] = 0;
    histo_yellow[i] = 0;
    histo_magenta[i] = 0;
}

const NO_OP                    =  0;
const SATURATION_CONTROL       =  1;
const INTENSITY_CONTROL        =  2;
const LEVEL_INPUT_LOWER_BOUND  =  3;
const LEVEL_INPUT_UPPER_BOUND  =  4;
const LEVEL_OUTPUT_LOWER_BOUND =  5;
const LEVEL_OUTPUT_UPPER_BOUND =  6;
const NEGATE                   =  7;
const FLIP_X                   =  8;
const FLIP_Y                   =  9;
const COLOR_BALANCE            = 10;

var command = NO_OP;

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

class codip_core {

  constructor(gl) {
        this.gl = gl;
        this.z = 3;
    }
}

cc = new codip_core();

</script>
