<script>

var levels_curve_color='#dddddd';

</script>

<div class="edit-module" id="edit-mod-curve-based-levels" hidden=true>
<p> <strong> COURBE </strong> </p>

<div class="dot-small bg-red" onclick="switch_curve_levels_channel('red')">   </div>
<div class="dot-small bg-green" onclick="switch_curve_levels_channel('green')"> </div>
<div class="dot-small bg-blue"  onclick="switch_curve_levels_channel('blue')">  </div>
<div class="dot-small bg-white"  onclick="switch_curve_levels_channel('white')"> </div>

<canvas id="niveaux-courbe"></canvas>
</div>

<script>

var canvas_niveaux_courbe = document.getElementById("niveaux-courbe");
var ctx = canvas_niveaux_courbe.getContext("2d");

canvas_niveaux_courbe.addEventListener('mousemove', function(evt) {
  var mousePos = getMousePos(canvas_niveaux_courbe, evt);
  var message =
    'Coord pointeur histo: ' + mousePos.x + ',' + mousePos.y;
  console.log(canvas_niveaux_courbe, message);
}, false);
canvas_niveaux_courbe.addEventListener('click', function(evt) {
  var mousePos = getMousePos(canvas_niveaux_courbe, evt);
  var message = 'Clic !';
  console.log(canvas_niveaux_courbe, message);
}, false);

var cnc_w = canvas_niveaux_courbe.width;
var cnc_h = canvas_niveaux_courbe.height;

ctx.strokeStyle = '#555555';
ctx.beginPath();
ctx.moveTo(cnc_w/2, 0);
ctx.lineTo(cnc_w/2,cnc_h);
ctx.stroke();
ctx.beginPath();
ctx.moveTo(cnc_w/4, 0);
ctx.lineTo(cnc_w/4,cnc_h);
ctx.stroke();
ctx.beginPath();
ctx.moveTo(cnc_w/4*3, 0);
ctx.lineTo(cnc_w/4*3,cnc_h);
ctx.stroke();

ctx.beginPath();
ctx.moveTo(0,cnc_h/2);
ctx.lineTo(cnc_w,cnc_h/2);
ctx.stroke();
ctx.beginPath();
ctx.moveTo(0,cnc_h/4);
ctx.lineTo(cnc_w,cnc_h/4);
ctx.stroke();
ctx.beginPath();
ctx.moveTo(0,cnc_h/4*3);
ctx.lineTo(cnc_w,cnc_h/4*3);
ctx.stroke();

ctx.strokeStyle = '#888888';
ctx.beginPath();
ctx.moveTo(0,cnc_h);
ctx.lineTo(cnc_w,0);
ctx.stroke();

var cp0x = 0;    var cp0y = cnc_h;
var cp1x = cnc_w/4*3;  var cp1y = cnc_h;
var cp2x = cnc_w/4;    var cp2y = 0;
var cp3x = cnc_w;var cp3y = 0;

function draw_levels_curve(curve_color) {
    ctx.strokeStyle = curve_color;
    ctx.beginPath();
    ctx.moveTo(cp0x,cp0y);
    ctx.bezierCurveTo(cp1x, cp1y, cp2x, cp2y, cp3x, cp3y);
    ctx.stroke();
}

draw_levels_curve(levels_curve_color);

ctx.fillStyle = '#eeeeee';
ctx.fillRect (cp1x-3, cp1y-3, 6, 6);
ctx.fillRect (cp2x-3, cp2y-3, 6, 6);

// draw histo blue
ctx.strokeStyle = '#bbbbbb';
for(var i=0 ; i < 255 ; i++) {
    ctx.beginPath();
    ctx.moveTo(0,cnc_h);
    ctx.lineTo(0,cnc_h*(1-histo_blue[i]));
    ctx.stroke();
}

function switch_curve_levels_channel(channel) {

  if(channel == 'red') {
      levels_curve_color='red';
      draw_levels_curve(levels_curve_color);
  }
  else if(channel == 'green') {
      levels_curve_color='green';
      draw_levels_curve(levels_curve_color);
  }
  else if(channel == 'blue') {
      levels_curve_color='blue';
      draw_levels_curve(levels_curve_color);
  }
  else if(channel == 'white') {
      levels_curve_color='#dddddd';
      draw_levels_curve(levels_curve_color);
  }
}

</script>
