<div class="edit-module" id="edit-mod-luminosity" hidden=true>
  <p> <strong> LUMINOSITE </strong> </p>
  <input type="range" min="-100" max="100" value="0" class="slider" id="lumi"/>
  <script>
    var curseur_lumi = document.getElementById("lumi");
    curseur_lumi.oninput = function() {
      gl.uniform1f(u_lumi_coeff, curseur_lumi.value/100.0);
      gl.uniform1ui(u_command, INTENSITY_CONTROL);
      gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
      }
  </script>
</div>
