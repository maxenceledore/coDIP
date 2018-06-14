<script>
function zoom_in() {
  if(zoom <= 32)
      zoom *=2;
  else
      return;
  gl.uniform1f(u_scale, zoom);
  gl.bindFramebuffer(gl.FRAMEBUFFER, null);
  gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
}

function zoom_out() {
  if(zoom >= 0.03125)
      zoom /=2;
  else
      return;
  gl.uniform1f(u_scale, zoom);
  gl.bindFramebuffer(gl.FRAMEBUFFER, null);
  gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
}
</script>
