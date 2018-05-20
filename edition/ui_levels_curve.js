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

      var cp0x = 0;          var cp0y = cnc_h;
      var cp1x = cnc_w/4*3;  var cp1y = cnc_h;
      var cp2x = cnc_w/4;    var cp2y = 0;
      var cp3x = cnc_w;      var cp3y = 0;

      ctx.strokeStyle = '#dddddd';
      ctx.beginPath();
      ctx.moveTo(cp0x,cp0y);
      ctx.bezierCurveTo(cp1x, cp1y, cp2x, cp2y, cp3x, cp3y);
      ctx.stroke();

      ctx.fillStyle = '#eeeeee';
      ctx.fillRect (cp1x-3, cp1y-3, 6, 6);
      ctx.fillRect (cp2x-3, cp2y-3, 6, 6);
