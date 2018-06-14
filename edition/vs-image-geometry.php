<script id="vs" type="application/x-glsl">

#version 300 es
precision mediump float;
precision mediump int;
//#pragma STDGL invariant(all)

#if __VERSION__== 100

#define in                      attribute
#define out                     varying

#define essl100_compat_texture1D(sampler2D,vec3) texture1D(sampler1D,float)
#define essl100_compat_texture2D(sampler2D,vec2) texture2D(sampler2D,vec2)
#define essl100_compat_texture3D(sampler2D,vec3) texture3D(sampler2D,vec3)

in vec2 pos;
in vec2 texcoord;

#endif

#if __VERSION__== 300

#define essl100_compat_texture1D(sampler2D,vec3) texture(sampler1D,float)
#define essl100_compat_texture2D(sampler2D,vec2) texture(sampler2D,vec2)
#define essl100_compat_texture3D(sampler2D,vec3) texture(sampler2D,vec3)

#define POSITION_LOCATION 0
#define TEXCOORD_LOCATION 1

layout(location = POSITION_LOCATION) in vec2 pos;
layout(location = TEXCOORD_LOCATION) in vec2 texcoord;

#endif

uniform float scale;
uniform float flipy;
uniform mat2  planar_rotation;

uniform int command;

out vec2 tc;

void main() {

  int cmd = command;
  vec2 transformed_pos = scale*pos;

  transformed_pos.y*= flipy;
  transformed_pos  *= planar_rotation;

  gl_Position = vec4(transformed_pos, 0.0, 1.0);
  tc = texcoord;
}

</script>
