<div class="edit-module" id="edit-mod-luminosity" hidden=true>
  <p class="centrage"> <strong> LUMINOSITE </strong> </p>
  <input type="range" min="-100" max="100" value="0" class="slider" id="lumi"/>
  <input type="number" min="-100" max="100" value="0" id="lumi-value-field"/>
  <script>
    var curseur_lumi = document.getElementById("lumi");
    var lumi_value_field = document.getElementById("lumi-value-field");
    var luminosity = lumi_value_field.value;

    curseur_lumi.oninput = function() {
      lumi_value_field.value = curseur_lumi.value;
      luminosity = curseur_lumi.value;
      set_luminosity();
      }
    lumi_value_field.oninput = function() {
      curseur_lumi.value = lumi_value_field.value;
      luminosity = curseur_lumi.value;
      set_luminosity();
    }

    function set_luminosity() {
      gl.uniform1f(u_lumi_coeff, curseur_lumi.value/100.0);
      gl.uniform1ui(u_command, INTENSITY_CONTROL);
      gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
      }
  </script>
</div>
