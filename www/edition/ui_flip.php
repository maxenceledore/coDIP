<script>
function flip() {
  flip_y *= -1.0;
  console.log(flip_y);
  gl.uniform1f(u_flip_y, flip_y);
  gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
  draw_preview_layer();
}
</script>
