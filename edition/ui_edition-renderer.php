<script>

'use strict';

function createShader(gl, source, type) {
    const shader = gl.createShader(type);
    gl.shaderSource(shader, source);
    gl.compileShader(shader);
    return shader;
}

function createProgram (gl, vsSrc, fsSrc) {
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
    var src = document.getElementById(id).textContent;
    if(WebGLversion == 1)
        src = src.replace('version 300 es', 'version 100');
    src = src.replace(/^\s+|\s+$/g, '');
    return src;
};

function create_WebGL_Context(canvas_id) {

    canvasRenduOpengl = document.querySelector(canvas_id);

    var gl = canvasRenduOpengl.getContext('webgl2', { antialias: false, depth: false,
                                                      preserveDrawingBuffer: true });
    const isWebGL2 = !!gl;
    if(!isWebGL2) {
        gl = canvasRenduOpengl.getContext('webgl', { antialias: false, depth: false,
                                                     preserveDrawingBuffer: true });
        const isWebGL1 = !!gl;
        if(!isWebGL1) {
            window.alert('coDIP requires at least WebGL 1\n');
            return null;
        }
            else {
                WebGLversion = 1;
                ESSL_version_string += "100\n";
                }
     }
     else {
         WebGLversion = 2;
         ESSL_version_string += "300 es\n";
     }
     return gl;
}

function create_layer() {
    layer0 = gl.createTexture();
    gl.activeTexture(gl.TEXTURE0);
    gl.bindTexture(gl.TEXTURE_2D, layer0);
    if(WebGLversion == 2)
        gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGB, img_w, img_h, 0, gl.RGB, gl.UNSIGNED_BYTE, image);
    else {
        gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGB, gl.RGB, gl.UNSIGNED_BYTE, image);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
    }
    image = null;
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);

    const imgtext_loc = gl.getUniformLocation(program, 'img');
    gl.uniform1i(imgtext_loc, 0);

};

function create_preview_layer () {
    previewlayerFB = gl.createFramebuffer();
    previewlayerFBtexture = gl.createTexture();

    gl.bindTexture(gl.TEXTURE_2D, previewlayerFBtexture);
    gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGB, img_w, img_h, 0, gl.RGB,
                  gl.UNSIGNED_BYTE, null);
    /* We don't want mipmap, we just want completeness asap */
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);

    gl.bindFramebuffer(gl.FRAMEBUFFER, previewlayerFB);
    gl.framebufferTexture2D(gl.FRAMEBUFFER, gl.COLOR_ATTACHMENT0,
                            gl.TEXTURE_2D, previewlayerFBtexture, 0/* level */);
    var status = (gl.checkFramebufferStatus(gl.FRAMEBUFFER) == gl.FRAMEBUFFER_COMPLETE);
    if(status == false)
        console.log('Incomplete preview layer FB');

    gl.bindFramebuffer(gl.FRAMEBUFFER, null);
}

function create_render_2_layer_FB () {
    render2layerFB = gl.createFramebuffer();
    gl.bindFramebuffer(gl.FRAMEBUFFER, render2layerFB);
    gl.bindFramebuffer(gl.FRAMEBUFFER, null);
    gl.finish();
}

function render_2_preview_layer() {
    gl.activeTexture(gl.TEXTURE0);
    gl.bindTexture(gl.TEXTURE_2D, layer0);
    gl.bindFramebuffer(gl.FRAMEBUFFER, previewlayerFB);
    gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
    gl.finish();
}

function draw_preview_layer() {
    gl.activeTexture(gl.TEXTURE0);
    gl.bindTexture(gl.TEXTURE_2D, previewlayerFBtexture);
    gl.bindFramebuffer(gl.FRAMEBUFFER, null);
    gl.uniform1i(u_command, NO_OP);
    gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
    gl.finish();
}

function copy_previewFB_2_layer() {
    gl.bindFramebuffer(gl.FRAMEBUFFER, render2layerFB);
    gl.framebufferTexture2D(gl.FRAMEBUFFER, gl.COLOR_ATTACHMENT0,
                            gl.TEXTURE_2D, layer0, 0/* level */);
    var status = (gl.checkFramebufferStatus(gl.FRAMEBUFFER) == gl.FRAMEBUFFER_COMPLETE);

    gl.uniform1i(u_command, NO_OP);
    gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
    gl.finish();
}

function render_2_workspace() {
    gl.bindFramebuffer(gl.FRAMEBUFFER, null);
    gl.uniform1i(u_command, NO_OP);
    gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
    gl.finish();
}

var gl = create_WebGL_Context("#gl-edit-render");
if(gl) {
    program = createProgram(gl, getShaderSource('vs'), getShaderSource('fs'));
    u_command        = gl.getUniformLocation(program, "command");
    u_flip_y         = gl.getUniformLocation(program, "flipy");
    u_scale          = gl.getUniformLocation(program, "scale");
    u_planar_rotation= gl.getUniformLocation(program, "planar_rotation");

    imgtext_loc  = gl.getUniformLocation(program, 'img');
    u_lumi_coeff = gl.getUniformLocation(program, "lumi_coeff");
    u_satu_coeff = gl.getUniformLocation(program, "satu_coeff");
    u_niv_ent_lim_basse  = gl.getUniformLocation(program, "niv_ent_lim_basse");
    u_niv_ent_lim_haute  = gl.getUniformLocation(program, "niv_ent_lim_haute");
    u_niv_sort_lim_basse = gl.getUniformLocation(program, "niv_sort_lim_basse");
    u_niv_sort_lim_haute = gl.getUniformLocation(program, "niv_sort_lim_haute");
    u_cb_gm_coeff = gl.getUniformLocation(program, "cb_gm_coeff");
    u_cb_by_coeff = gl.getUniformLocation(program, "cb_by_coeff");
    u_cb_rc_coeff = gl.getUniformLocation(program, "cb_rc_coeff");

    gl.useProgram(program);
    gl.uniform1i(u_command, NO_OP);

    gl.uniform1f(u_scale, 1.0);
    gl.uniform1f(u_flip_y, 1.0);

    gl.uniform1f(u_lumi_coeff, 0.0);  /* -100/0/+100 --> -1.0/0.0/+1.0 */
    gl.uniform1f(u_satu_coeff, 1.0);  /* -100/0/+100 --> 0.0/+1.0/+2.0 */
    gl.uniform1f(u_niv_ent_lim_basse,    0./255.);
    gl.uniform1f(u_niv_ent_lim_haute,  255./255.);
    gl.uniform1f(u_niv_sort_lim_basse,   0./255.);
    gl.uniform1f(u_niv_sort_lim_haute, 255./255.);
    //gl.uniform1i(u_lumi_courbe_niveau);
    //gl.uniform1i(u_rouge_courbe_niveau);
    //gl.uniform1i(u_vert_courbe_niveau);
    //gl.uniform1i(u_bleu_courbe_niveau);
    gl.uniform1f(u_cb_gm_coeff, 1.0);  /* -100/0/+100 --> 0.0/+1.0/+2.0 */
    gl.uniform1f(u_cb_by_coeff, 1.0);  /* -100/0/+100 --> 0.0/+1.0/+2.0 */
    gl.uniform1f(u_cb_rc_coeff, 1.0);  /* -100/0/+100 --> 0.0/+1.0/+2.0 */

    gl.uniformMatrix2fv(u_planar_rotation, false, rotation_mat);

    create_preview_layer();
    create_render_2_layer_FB();
}


if(WebGLversion == 2) {
    imgGeomTilingVAO = gl.createVertexArray();
    gl.bindVertexArray(imgGeomTilingVAO);
}

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

if(WebGLversion == 2) {
gl.bindVertexArray(null);
gl.bindVertexArray(imgGeomTilingVAO);
}

var image = new Image();

image.onload = function() {
    create_layer();
    render_2_preview_layer();
    gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
    draw_preview_layer();
    flip();
    copy_previewFB_2_layer();
    gl.finish();
};

image.src = img_path;

gl.clearColor(0.1, 0.1, 0.1, 1.0);
gl.clear(gl.COLOR_BUFFER_BIT);

</script>
