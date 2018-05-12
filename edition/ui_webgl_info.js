(function () {
const debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
if(debugInfo) {
  const vendor= gl.getParameter(debugInfo.UNMASKED_VENDOR_WEBGL);
  const renderer  = gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL);
  /* WebGL/OpenGL version/vend */
  console.log(vendor, renderer);
  }
const maxTextureSize  = gl.getParameter(gl.MAX_TEXTURE_SIZE);
const maxArrayTextureLayers = gl.getParameter(gl.MAX_ARRAY_TEXTURE_LAYERS);
const max3DTextureSize= gl.getParameter(gl.MAX_3D_TEXTURE_SIZE);
const maxRenderbufferSize = gl.getParameter(gl.MAX_RENDERBUFFER_SIZE);
const maxDrawBuffers  = gl.getParameter(gl.MAX_DRAW_BUFFERS);
const maxUniformBlockSize = gl.getParameter(gl.MAX_UNIFORM_BLOCK_SIZE);
const maxFragmentUniformVectors = gl.getParameter(gl.MAX_FRAGMENT_UNIFORM_VECTORS);
const maxFragmentUniformBlocks = gl.getParameter(gl.MAX_FRAGMENT_UNIFORM_BLOCKS);
const maxCombinedFragmentUniformComponents = gl.getParameter(gl.MAX_COMBINED_FRAGMENT_UNIFORM_COMPONENTS);

console.log('maxTextureSize:', maxTextureSize,'\n');
console.log('maxArrayTextureLayers :', maxArrayTextureLayers);
console.log('max3DTextureSize :', max3DTextureSize,'\n');
console.log('maxRenderbufferSize :', maxRenderbufferSize,'\n');
console.log('maxDrawBuffers :', maxDrawBuffers,'\n');
console.log('maxFragmentUniformVectors :', maxFragmentUniformVectors,'\n');
console.log('maxFragmentUniformBlocks :', maxFragmentUniformBlocks,'\n');
console.log('maxUniformBlockSize :', maxUniformBlockSize,'\n');
console.log('maxCombinedFragmentUniformComponents :', maxCombinedFragmentUniformComponents,'\n');
})();
