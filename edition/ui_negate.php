<script>
function negate() {
    gl.uniform1i(u_command, NEGATE);
    render_2_preview_layer();
    gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
    draw_preview_layer();
    copy_previewFB_2_layer();
    }
</script>
