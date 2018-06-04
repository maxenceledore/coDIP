<script id="vs" type="application/x-glsl">

#version 300 es
#define POSITION_LOCATION 0
#define TEXCOORD_LOCATION 1

precision highp float;
precision highp int;

layout(location = POSITION_LOCATION) in vec2 pos;
layout(location = TEXCOORD_LOCATION) in vec2 texcoord;

uniform float scale;
uniform mat2  planar_rotation;

uniform uint command;

out vec2 tc;

void main() {

  uint cmd = command;
  vec2 transformed_pos = scale*pos;

  transformed_pos *= planar_rotation;

  gl_Position = vec4(transformed_pos, 0.0f, 1.0f);
  tc = texcoord;
}

</script>
