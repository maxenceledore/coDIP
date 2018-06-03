    (function () {
    'use strict';

    function createShader(gl, source, type) {
        const shader = gl.createShader(type);
        gl.shaderSource(shader, source);
        gl.compileShader(shader);
        return shader;
    }

    window.createProgram = function(gl, vsSrc, fsSrc) {
        const program = gl.createProgram();
        const vshader = createShader(gl, vsSrc, gl.VERTEX_SHADER);
        const fshader = createShader(gl, fsSrc, gl.FRAGMENT_SHADER);

        if(!gl.getShaderParameter(vshader, gl.COMPILE_STATUS))
           console.log(gl.getShaderInfoLog(vshader));

        if(!gl.getShaderParameter(fshader, gl.COMPILE_STATUS))
           console.log(gl.getShaderInfoLog(fshader));

        var log = gl.getShaderInfoLog(vshader);
        if(log)
           console.log(log);

        log = gl.getShaderInfoLog(fshader);
        if(log)
           console.log(log);

        log = gl.getProgramInfoLog(program);
        if(log)
           console.log(log);

        gl.attachShader(program, vshader);
        gl.attachShader(program, fshader);
        gl.deleteShader(vshader);
        gl.deleteShader(fshader);
        gl.linkProgram(program);

        return program;
    };

    window.getShaderSource = function(id) {
        return document.getElementById(id).textContent.replace(/^\s+|\s+$/g, '');
    };

})();

    const canvasRenduOpengl = document.querySelector("#gl-edit-render");

        const gl = canvasRenduOpengl.getContext('webgl2', { antialias: false, depth: false });
        const isWebGL2 = !!gl;
        if(!isWebGL2) {
            window.alert(`
            Gallerie requiert WebGL 2 (OpenGL ES 3)\n
            Les navigateurs suivants supportent WebGL 2 :\n
            - Firefox 51\n
            - Chrome 46\n
            - Safari 11 (activation manuelle)
            `);
        }

        const NO_OP                    =  0;
        const SATURATION_CONTROL       =  1;
        const INTENSITY_CONTROL        =  2;
        const LEVEL_INPUT_LOWER_BOUND  =  3;
        const LEVEL_INPUT_UPPER_BOUND  =  4;
        const LEVEL_OUTPUT_LOWER_BOUND =  5;
        const LEVEL_OUTPUT_UPPER_BOUND =  6;

        var command = NO_OP;
        var program = createProgram(gl, getShaderSource('vs'), getShaderSource('fs'));

        var u_scale      = gl.getUniformLocation(program, "scale");

        var imgtext_loc  = gl.getUniformLocation(program, 'img');
        var u_lumi_coeff = gl.getUniformLocation(program, "lumi_coeff");
        var u_satu_coeff = gl.getUniformLocation(program, "satu_coeff");
        var u_niv_ent_lim_basse  = gl.getUniformLocation(program, "niv_ent_lim_basse");
        var u_niv_ent_lim_haute  = gl.getUniformLocation(program, "niv_ent_lim_haute");
        var u_niv_sort_lim_basse = gl.getUniformLocation(program, "niv_sort_lim_basse");
        var u_niv_sort_lim_haute = gl.getUniformLocation(program, "niv_sort_lim_haute");
        var u_command = gl.getUniformLocation(program, "command");

        gl.useProgram(program);

        gl.uniform1f(u_scale, 1.0);

        gl.uniform1f(u_lumi_coeff, 0.0);  /* -100/0/+100 --> -1.0/0.0/+1.0 */
        gl.uniform1f(u_satu_coeff, 1.0);  /* -100/0/+100 --> 0.0/+1.0/+2.0 */
        gl.uniform1f(u_niv_ent_lim_basse,    0./255.);
        gl.uniform1f(u_niv_ent_lim_haute,  255./255.);
        gl.uniform1f(u_niv_sort_lim_basse,   0./255.);
        gl.uniform1f(u_niv_sort_lim_haute, 255./255.);
//      gl.uniform1i(u_lumi_courbe_niveau);
//      gl.uniform1i(u_rouge_courbe_niveau);
//      gl.uniform1i(u_vert_courbe_niveau);
//      gl.uniform1i(u_bleu_courbe_niveau);
        gl.uniform1ui(u_command, NO_OP);

        const vertexPosLocation = 0;
        const vertices = new Float32Array([
            -1.0, -1.0,
             1.0, -1.0,
            -1.0,  1.0,
             1.0,  1.0
        ]);

        const texCoordsLocation = 1;

        const texCoords = new Float32Array([
             0.0, 1.0,
             1.0, 1.0,
             0.0, 0.0,
             1.0, 0.0
        ]);

        const vertexArray = gl.createVertexArray();
        gl.bindVertexArray(vertexArray);

        const vertexPosBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, vertexPosBuffer);
        gl.bufferData(gl.ARRAY_BUFFER, vertices, gl.STATIC_DRAW);
        gl.enableVertexAttribArray(vertexPosLocation);
        gl.vertexAttribPointer(vertexPosLocation, 2, gl.FLOAT, false, 0, 0);

        const texCoordBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, texCoordBuffer);
        gl.bufferData(gl.ARRAY_BUFFER, texCoords, gl.STATIC_DRAW);
        gl.enableVertexAttribArray(texCoordsLocation);
        gl.vertexAttribPointer(texCoordsLocation, 2, gl.FLOAT, false, 0, 0);


        gl.bindVertexArray(null);
        gl.bindVertexArray(vertexArray);

        const image = new Image();

        image.onload = function() {
            const texture = gl.createTexture();
            gl.activeTexture(gl.TEXTURE0);
            gl.bindTexture(gl.TEXTURE_2D, texture);
            gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGB, img_w, img_h, 0, gl.RGB, gl.UNSIGNED_BYTE, image);
            delete image;
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);

            const imgtext_loc = gl.getUniformLocation(program, 'img');
            gl.uniform1i(imgtext_loc, 0);

            gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);

            /* evaluate histogram */
/*
            var histo = document.createElement('canvas');
            histo.id = "histo";
            var ctx = histo.getContext('2d');
            ctx.canvas.width = img_w;
            ctx.canvas.height = img_h;
            ctx.drawImage(image, 0, 0,img_w,img_h);
            image.style.display = 'none';
            for(var i=0 ; i < img_w ; i++) {
                for(var j=0 ; j < img_h ; j++) {
                    var pixel = ctx.getImageData(i, j, 1, 1);
                    var data = pixel.data;
                    var r = data[0];
                    var g = data[1];
                    var b = data[2];
                    histo_blue[r] += 1;
                    histo_green[g]+= 1;
                    histo_red[b]  += 1;
                }
            }
            pixel_count = img_w*img_h;
            for(var i=0 ; i < 255 ; i++) {
                histo_blue[i] /= pixel_count;
                histo_green[i]/= pixel_count;
                histo_red[i]  /= pixel_count;
            }
            var dump ='';
            for(var i=0 ; i < 255 ; i++) {
                dump += histo_blue[i] + ',';
            }
            console.log(dump);
            histo.remove();
*/
            };
        image.src = img_path;

        gl.clearColor(0.1, 0.1, 0.1, 1.0);
        gl.clear(gl.COLOR_BUFFER_BIT);
