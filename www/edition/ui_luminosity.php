<div class="edit-module" id="edit-mod-luminosity" hidden=true>
  <p class="centrage"> <strong> LUMINOSITE </strong> </p>
  <input type="range" min="-100" max="100" value="0" class="slider" id="lumi"/>
  <input type="number" min="-100" max="100" value="0" id="lumi-value-field"/>

  <div class="centrage">
  <button class="clickable" id="luminosity-reset-btn"> RESET </button>
  <button class="clickable" id="luminosity-apply-btn"> APPLY </button>
  </div>

  <script>
    var curseur_lumi = document.getElementById("lumi");
    var lumi_value_field = document.getElementById("lumi-value-field");
    var luminosity = lumi_value_field.value;

    var luminosity_reset_btn = document.getElementById("luminosity-reset-btn");
    luminosity_reset_btn.onclick = reset_luminosity;

    var luminosity_apply_btn = document.getElementById("luminosity-apply-btn");
    luminosity_apply_btn.onclick = save_luminosity;

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
      gl.uniform1i(u_command, INTENSITY_CONTROL);
      render_2_preview_layer();
      gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
      draw_preview_layer();
      }

    function reset_luminosity() {
      lumi_value_field.value = 0;
      curseur_lumi.value     = 0;
      set_luminosity();
      }
    function save_luminosity() {
      copy_previewFB_2_layer();
      }
  </script>
</div>
