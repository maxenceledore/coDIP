function getMousePos(canvas, evt) {
    var rect = canvas.getBoundingClientRect();
    return {
       x: parseInt(evt.clientX - rect.left, 10),
       y: parseInt(evt.clientY - rect.top, 10)
       };
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
