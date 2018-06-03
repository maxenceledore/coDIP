<script>
function negate() {
    gl.uniform1ui(u_command, NEGATE);
    gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
    
    }
</script>
