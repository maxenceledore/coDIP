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
    if(charCode == 43) {
        console.log("zoom_in");
    }
    else if(charCode == 45) {
        console.log("zoom_out");
    }
}
