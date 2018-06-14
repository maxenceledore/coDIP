<div class="edit-module" id="edit-mod-color-balance" hidden=true>
<p class="centrage"> <strong> COLOR BALANCE </strong> </p>

<p class="centrage"> Green-Magenta </p>
<input type="range" min="-100" max="100" value="0" class="slider magenta2green" id="balance-magenta2green">

<p class="centrage"> Blue-Yellow </p>
<input type="range" min="-100" max="100" value="0" class="slider yellow2blue" id="balance-yellow2blue">

<p class="centrage"> Red-Cyan </p>
<input type="range" min="-100" max="100" value="0" class="slider cyan2red" id="balance-cyan2red">

<p class="centrage"> Preserve luminosity
<input type="checkbox" id="preserve-luminosity" checked>
</p>

<div class="centrage"> 
<button class="clickable" id="cb_reset_btn"> RESET </button>
<button class="clickable" id="cb_apply_btn"> APPLY </button>
</div>

</div>

<script>
    var gm_balance_slider = document.getElementById("balance-magenta2green");
    var by_balance_slider = document.getElementById("balance-yellow2blue");
    var rc_balance_slider = document.getElementById("balance-cyan2red");

    var cb_reset_btn = document.getElementById("cb_reset_btn");
    cb_reset_btn.onclick = reset_color_balance;

    var cb_apply_btn = document.getElementById("cb_apply_btn");
    cb_apply_btn.onclick = save_color_balance;

    var gm_balance = gm_balance_slider.value;
    var by_balance = by_balance_slider.value;
    var rc_balance = rc_balance_slider.value;

    gm_balance_slider.oninput = function() {
      gm_balance = gm_balance_slider.value;
      set_color_balance();
      }

    by_balance_slider.oninput = function() {
      by_balance = by_balance_slider.value;
      set_color_balance();
      }

    rc_balance_slider.oninput = function() {
      rc_balance = rc_balance_slider.value;
      set_color_balance();
      }

    function set_color_balance() {
      const COEFF_INTERNAL_MIN = 0.0;
      const COEFF_INTERNAL_MAX = 2.0;
      const UI_CB_VAL_MIN = gm_balance_slider.min;
      const UI_CB_VAL_MAX = gm_balance_slider.max;
      const m = (COEFF_INTERNAL_MAX-COEFF_INTERNAL_MIN)/(UI_CB_VAL_MAX-UI_CB_VAL_MIN);
      const p = COEFF_INTERNAL_MAX - m*UI_CB_VAL_MAX;
      const gm_coeff = m * gm_balance + p;
      const by_coeff = m * by_balance + p;
      const rc_coeff = m * rc_balance + p;
      gl.uniform1f(u_cb_gm_coeff, gm_coeff);
      gl.uniform1f(u_cb_by_coeff, by_coeff);
      gl.uniform1f(u_cb_rc_coeff, rc_coeff);
      gl.uniform1i(u_command, COLOR_BALANCE);
      render_2_preview_layer();
      gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
      draw_preview_layer();
    }
    function set_color_balance_preserving_lumi() {
      // TSL based. will be the default option soon.
    }
    function reset_color_balance() {
      gm_balance_slider.value = 0;
      by_balance_slider.value = 0;
      rc_balance_slider.value = 0;
      gm_balance = gm_balance_slider.value;
      by_balance = by_balance_slider.value;
      rc_balance = rc_balance_slider.value;
      set_color_balance();
    }
    function save_color_balance() {
      copy_previewFB_2_layer();
      }
</script>
