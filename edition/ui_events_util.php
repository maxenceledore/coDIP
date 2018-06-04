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
    if(charCode == 43)
        zoom_in();
    // pressed numpad -
    else if(charCode == 45)
        zoom_out();
}

</script>
