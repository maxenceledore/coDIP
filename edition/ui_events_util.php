<script>
function getMousePos(canvas, evt) {
    var rect = canvas.getBoundingClientRect();
    return {
       x: parseInt(evt.clientX - rect.left, 10),
       y: parseInt(evt.clientY - rect.top, 10)
       };
    }

function get_pixel_click_coords(event) {
    var bounding_rect =
    document.getElementById('gl-edit-render').getBoundingClientRect();
    var x = (event.clientX-bounding_rect.left)/event.clientX;
    var y = (event.clientY-bounding_rect.top)/event.clientY;
    var clicked_pix_x = x*img_w;
    var clicked_pix_y = y*img_h;
}


window.onkeypress =  handle_keypress;
function handle_keypress(event){
    var charCode = (typeof event.which == "number") ? event.which : event.keyCode
    // pressed numpad +
    if(charCode == 43) {
        if(zoom <= 32)
          zoom *=2;
        gl.uniform1f(u_scale, zoom);
        gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
     // console.log("zoom_in :", zoom);
    }
    // pressed numpad -
    else if(charCode == 45) {
        if(zoom >= 0.03125)
          zoom /=2;
        gl.uniform1f(u_scale, zoom);
        gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
     // console.log("zoom_out :", zoom);
    }
    // console.log('CharCode :', charCode);
}

</script>
