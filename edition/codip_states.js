const VERSION = 'coDIP 0.04';

var zoom   = 1.0;
var offset = 0.0;

var histo_blue    = new Float32Array(255);
var histo_green   = new Float32Array(255);
var histo_red     = new Float32Array(255);
var histo_cyan    = new Float32Array(255);
var histo_yellow  = new Float32Array(255);
var histo_magenta = new Float32Array(255);

for(var i=0 ; i < 255 ; i++) {
    histo_blue[i] = 0;
    histo_green[i] = 0;
    histo_red[i] = 0;
    histo_cyan[i] = 0;
    histo_yellow[i] = 0;
    histo_magenta[i] = 0;
}
