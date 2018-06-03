<div class="edit-module" id="edit-mod-levels" hidden=true>
<p> <strong> NIVEAUX </strong> </p>

<div class="dot-small bg-red" onclick="switch_levels_channel('red')">   </div>
<div class="dot-small bg-green" onclick="switch_levels_channel('green')"> </div>
<div class="dot-small bg-blue"  onclick="switch_levels_channel('blue')">  </div>
<div class="dot-small bg-white"  onclick="switch_levels_channel('white')"> </div>

<script>

function switch_levels_channel(channel) {

  if(channel == 'red') {
    var curseur_niv_elh = document.getElementById("niv-ent-lim-haute");
    curseur_niv_elh.className = "slider b2r-hori";
    var curseur_niv_elh = document.getElementById("niv-sort-lim-haute");
    curseur_niv_slh.className = "slider b2r-hori";
    }
  else if(channel == 'green') {
    var curseur_niv_elh = document.getElementById("niv-ent-lim-haute");
    curseur_niv_elh.className = "slider b2g-hori";
    var curseur_niv_elh = document.getElementById("niv-sort-lim-haute");
    curseur_niv_slh.className = "slider b2g-hori";
    }
  else if(channel == 'blue') {
    var curseur_niv_elh = document.getElementById("niv-ent-lim-haute");
    curseur_niv_elh.className = "slider b2blue-hori";
    var curseur_niv_elh = document.getElementById("niv-sort-lim-haute");
    curseur_niv_slh.className = "slider b2blue-hori";
    }
  else if(channel == 'white') {
    var curseur_niv_elh = document.getElementById("niv-ent-lim-haute");
    curseur_niv_elh.className = "slider b2w-hori";
    var curseur_niv_elh = document.getElementById("niv-sort-lim-haute");
    curseur_niv_slh.className = "slider b2w-hori";
    }
}

</script>


<p> ENTREE </p>
<input type="range" min="0" max="255" value="0" class="slider" id="niv-ent-lim-basse">
  <span id="val-niv-ent-lim-basse"></span>
<script>
  var curseur_niv_elb = document.getElementById("niv-ent-lim-basse");
  curseur_niv_elb.oninput = function() {
    const UI_ELB_VAL_MAX = curseur_niv_elb.max;
    gl.uniform1f(u_niv_ent_lim_basse, curseur_niv_elb.value/UI_ELB_VAL_MAX);
    gl.uniform1ui(u_command, LEVEL_INPUT_LOWER_BOUND);
    gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
  }
</script>

<input type="range" min="0" max="255" value="255" class="slider b2w-hori" id="niv-ent-lim-haute">
<script>
  var curseur_niv_elh = document.getElementById("niv-ent-lim-haute");
  curseur_niv_elh.oninput = function() {
    const UI_ELH_VAL_MAX = curseur_niv_elh.max;
    gl.uniform1f(u_niv_ent_lim_haute, curseur_niv_elh.value/UI_ELH_VAL_MAX);
    gl.uniform1ui(u_command, LEVEL_INPUT_UPPER_BOUND);
    gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
  }
</script>

<p> SORTIE </p>
<input type="range" min="0" max="255" value="0" class="slider" id="niv-sort-lim-basse"/>
<script>
  var curseur_niv_slb = document.getElementById("niv-sort-lim-basse");
  curseur_niv_slb.oninput = function() {
    const UI_SLB_VAL_MAX = curseur_niv_slb.max;
    gl.uniform1f(u_niv_sort_lim_basse, curseur_niv_slb.value/UI_SLB_VAL_MAX);
    gl.uniform1ui(u_command, LEVEL_OUTPUT_LOWER_BOUND);
    gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
  }
</script>

<input type="range" min="0" max="255" value="255" class="slider b2w-hori" id="niv-sort-lim-haute"/>
<script>
  var curseur_niv_slh = document.getElementById("niv-sort-lim-haute");
  curseur_niv_slh.oninput = function() {
    const UI_SLH_VAL_MAX = curseur_niv_slh.max;
    gl.uniform1f(u_niv_sort_lim_haute, curseur_niv_slh.value/UI_SLH_VAL_MAX);
    gl.uniform1ui(u_command, LEVEL_OUTPUT_UPPER_BOUND);
    gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
  }
</script>
</div>
