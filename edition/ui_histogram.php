<div class="edit-module" id="edit-mod-histogram">
  <p class="centrage"> <strong> HISTOGRAMME </strong> </p>
  <canvas id="histogram"></canvas>
</div>

<script>

var canvashisto_canvas = document.getElementById("histogram");
var ctx = canvashisto_canvas.getContext("2d");

canvashisto_canvas.addEventListener('mousemove', function(evt) {
  var mousePos = getMousePos(canvashisto_canvas, evt);
  var message =
    'Coord pointeur histo: ' + mousePos.x + ',' + mousePos.y;
  console.log(canvashisto_canvas, message);
}, false);
canvashisto_canvas.addEventListener('click', function(evt) {
  var mousePos = getMousePos(canvashisto_canvas, evt);
  var message = 'Clic !';
  console.log(canvashisto_canvas, message);
}, false);

var cnc_w = canvashisto_canvas.width;
var cnc_h = canvashisto_canvas.height;

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

</script>
