<div class="edit-module" id="edit-mod-saturation" hidden=true>
  <p class="centrage"> <strong> SATURATION </strong> </p>
  <input type="range" min="-100" max="100" value="0" class="slider" id="satu"/>
  <input type="number" min="-100" max="100" value="0" id="satu-value-field"/>

  <div class="centrage">
  <button class="clickable" id="saturation-reset-btn"> RESET </button>
  <button class="clickable" id="saturation-apply-btn"> APPLY </button>
  </div>

  <script>
    var curseur_satu = document.getElementById("satu");
    var satu_value_field = document.getElementById("satu-value-field");
    var saturation = satu_value_field.value;

    var saturation_reset_btn = document.getElementById("saturation-reset-btn");
    saturation_reset_btn.onclick = reset_saturation;

    var saturation_apply_btn = document.getElementById("saturation-apply-btn");
    saturation_apply_btn.onclick = save_saturation;

    curseur_satu.oninput = function() {
      satu_value_field.value = curseur_satu.value;
      saturation = curseur_satu.value;
      set_saturation();
      }
    satu_value_field.oninput = function() {
      curseur_satu.value = satu_value_field.value;
      saturation = curseur_satu.value;
      set_saturation();
    }

    function set_saturation() {
      const COEFF_INTERNAL_MIN = 0.0;
      const COEFF_INTERNAL_MAX = 2.0;
      const UI_SATU_VAL_MIN = curseur_satu.min;
      const UI_SATU_VAL_MAX = curseur_satu.max;
      const m = (COEFF_INTERNAL_MAX-COEFF_INTERNAL_MIN)/(UI_SATU_VAL_MAX-UI_SATU_VAL_MIN);
      const p = COEFF_INTERNAL_MAX - m*UI_SATU_VAL_MAX;
      const coeff = m*curseur_satu.value+p;
      gl.uniform1f(u_satu_coeff, coeff);
      gl.uniform1i(u_command, SATURATION_CONTROL);
      render_2_preview_layer();
      gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
      draw_preview_layer();
      }

    function reset_saturation() {
      satu_value_field.value = 0;
      curseur_satu.value     = 0;
      set_saturation();
      }
    function save_saturation() {
      copy_previewFB_2_layer();
      }
  </script>
</div>
