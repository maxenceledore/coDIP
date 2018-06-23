<div class="edit-module" id="edit-mod-rotate" hidden=true>
  <p class="centrage"> <strong> ROTATE </strong> </p>
  <p class="centrage"> Angle </p>
  <input type="range" min="-180" max="180" value="0" class="slider" id="rotation-angle"/>
  <input type="number" min="-180" max="180" value="0" step="0.25" id="rotation-angle-value"/>

  <div class="centrage">
  <button class="clickable" id="rotation-reset-btn"> RESET </button>
  <button class="clickable" id="rotation_apply_btn"> APPLY </button>
  </div>

  </p>
  <script>
    var curseur_rotation     = document.getElementById("rotation-angle");
    var rotation_value_field = document.getElementById("rotation-angle-value");
    var degree = rotation_value_field.value;

    var rotation_reset_btn = document.getElementById("rotation-reset-btn");
    rotation_reset_btn.onclick = reset_rotation;

    var rotation_apply_btn = document.getElementById("rotation_apply_btn");
    rotation_apply_btn.onclick = save_rotation;

    curseur_rotation.oninput = function() {
      rotation_value_field.value = curseur_rotation.value;
      degree = curseur_rotation.value;
      rotate(degree);
      }
    rotation_value_field.oninput = function() {
      curseur_rotation.value = rotation_value_field.value;
      degree = curseur_rotation.value;
      rotate(degree);
    }
    function rotate(degree_value) {
      var radian = degree_value*Math.PI/180.0;
      var cos_angle = Math.cos(radian);
      var sin_angle = Math.sin(radian);
      rotation_mat = new Float32Array([cos_angle,sin_angle,-sin_angle,cos_angle]);
      gl.uniformMatrix2fv(u_planar_rotation, false, rotation_mat);
      gl.clear(gl.COLOR_BUFFER_BIT);
      gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
      draw_preview_layer();
    }

    function reset_rotation() {
      rotation_value_field.value = 0;
      curseur_rotation.value     = 0;
      degree = 0;
      rotate(degree);
    }
    function save_rotation() {
      copy_previewFB_2_layer();
    }
  </script>
</div>
