<div class="edit-module" id="edit-mod-rotate" hidden=true>
  <p class="centrage"> <strong> ROTATE </strong> </p>
  <p class="centrage">
  <input type="range" min="-180" max="180" value="0" class="slider" id="rotation-angle"/>
  <input type="number" min="-180" max="180" value="0" id="rotation-angle-value"/>
  </p>
  <script>
    var curseur_rotation     = document.getElementById("rotation-angle");
    var rotation_value_field = document.getElementById("rotation-angle-value");
    var degree = rotation_value_field.value;
    curseur_rotation.oninput = function() {
      rotation_value_field.value = curseur_rotation.value;
      degree = curseur_rotation.value;
      rotate();
      }
    rotation_value_field.oninput = function() {
      curseur_rotation.value = rotation_value_field.value;
      degree = curseur_rotation.value;
      rotate();
    }
    function rotate() {
      var radian = degree*Math.PI/180.0;
      var cos_angle = Math.cos(radian);
      var sin_angle = Math.sin(radian);
      rotation_mat = new Float32Array([cos_angle,sin_angle,-sin_angle,cos_angle]);
      gl.uniformMatrix2fv(u_planar_rotation, false, rotation_mat);
      gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
    }
  </script>
</div>
