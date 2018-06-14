<script id="fs" type="application/x-glsl">

#version 300 es

precision mediump float;
precision mediump int;

#if __VERSION__== 100

/* #ifdef GL_FRAGMENT_PRECISION_HIGH */
/* precision highp float; */
/* precision highp int; */
/* #else */

/* #endif */

#define in                      varying
#define FragColor               gl_FragColor

#define essl100_compat_texture1D(sampler2D,vec3) texture1D(sampler1D,float)
#define essl100_compat_texture2D(sampler2D,vec2) texture2D(sampler2D,vec2)
#define essl100_compat_texture3D(sampler2D,vec3) texture3D(sampler2D,vec3)

#endif

#if __VERSION__== 300

#define essl100_compat_texture1D(sampler2D,vec3) texture(sampler1D,float)
#define essl100_compat_texture2D(sampler2D,vec2) texture(sampler2D,vec2)
#define essl100_compat_texture3D(sampler2D,vec3) texture(sampler2D,vec3)

out vec4 FragColor;

#endif



uniform sampler2D img;
uniform sampler2D lumi_courbe_niveau;
uniform sampler2D rouge_courbe_niveau;
uniform sampler2D vert_courbe_niveau;
uniform sampler2D bleu_courbe_niveau;
uniform float     lumi_coeff;
uniform float     satu_coeff;
uniform float     niv_ent_lim_basse;
uniform float     niv_ent_lim_haute;
uniform float     niv_sort_lim_basse;
uniform float     niv_sort_lim_haute;
uniform float     cb_gm_coeff;
uniform float     cb_by_coeff;
uniform float     cb_rc_coeff;

uniform int command;

#define NO_OP                      0
#define SATURATION_CONTROL         1
#define INTENSITY_CONTROL          2
#define LEVEL_INPUT_LOWER_BOUND    3
#define LEVEL_INPUT_UPPER_BOUND    4
#define LEVEL_OUTPUT_LOWER_BOUND   5
#define LEVEL_OUTPUT_UPPER_BOUND   6
#define NEGATE                     7
#define COLOR_BALANCE             10

in vec2 tc;

vec3 rvb_vers_tsl (vec3 rvb) {

  // ([0-1],[0-1],[0-1])
     float R = rvb.x;
     float V = rvb.y;
     float B = rvb.z;

     float M = max(R,max(V,B));
     float m = min(R,min(V,B));
     float C = M-m; /* chroma */

     float T = 0.0;
     float S = 0.0;
     float L = 0.5*(M+m);

     if(L != 1.0)
       S = C/(1.0-abs(2.0*L-1.0));

     if(C == 0.0)
       T = 0.0;
     else if(M == R)
       T = mod(((V-B)/C), 6.0);

     else if(M == V)
       T = mod(((B-R)/C)+2.0, 6.0);

     else if(M == B)
       T = mod(((R-V)/C)+4.0, 6.0);

     T*= 60.0;

     return vec3(T,S,L);
}

vec3 tsl_vers_rvb (vec3 tsl) {

  // ([0-360],[0-1],[0-1])
     float T = clamp(tsl.x,0.0,360.0);
     float S = clamp(tsl.y,0.0,1.0);
     float L = clamp(tsl.z,0.0,1.0);

     vec3 rvb = vec3(0.0,0.0,0.0);

     float C = (1.0-abs(2.0*L-1.0))*S;
     float X = C*(1.0-abs(mod(T/60.0,2.0)-1.0));
     float m = L-0.5*C;

//   if(T == 0.0)
//     rvb = vec3(0.0,0.0,0.0);
//
//   else if((T/60.0 >= 0.0) && (T/60.0 < 1.0))

     if((T/60.0 >= 0.0) && (T/60.0 < 1.0))
rvb = vec3(C,X,0.0);

     else if((T/60.0 >= 1.0) && (T/60.0 < 2.0))
    rvb = vec3(X,C,0.0);

     else if((T/60.0 >= 2.0) && (T/60.0 < 3.0))
    rvb = vec3(0.0,C,X);

     else if((T/60.0 >= 3.0) && (T/60.0 < 4.0))
    rvb = vec3(0.0,X,C);

     else if((T/60.0 >= 4.0) && (T/60.0 < 5.0))
    rvb = vec3(X,0.0,C);
     else
rvb = vec3(C,0.0,X);

     rvb += vec3(m,m,m);

     return rvb;
}

vec3 highlight_frags_matching_color(vec3  tested,
    vec3  color_to_match,
    float threshold) {

     vec3 HIGHLIGHT_COLOR = vec3(0.0,0.0,255.0);

     float l = length(distance(tested, color_to_match));

     if(l < threshold)
       return mix(tested, HIGHLIGHT_COLOR, 0.01);

     return tested;
}

vec3 niveau_de_gris_tsl(vec3 rvb) {

     vec3 tsl = rvb_vers_tsl(rvb);

     return vec3(tsl.z);
}

vec3 niveau_de_gris_luminance(vec3 rvb) {

     vec3 coeff = vec3(0.2126,0.7152,0.0722);

     float l = dot(rvb,coeff);

     return vec3(l);
}

void main() {

   int cmd = command;

   vec3 pixel     = essl100_compat_texture2D(img, tc).xyz;
   vec3 pixel_tsl = vec3(0.0,0.0,0.0);

     if(cmd == SATURATION_CONTROL) {
         pixel_tsl = rvb_vers_tsl(pixel);
         pixel_tsl.y *= float(satu_coeff);
         pixel = tsl_vers_rvb(pixel_tsl);
         }
     if(cmd == INTENSITY_CONTROL) {
         pixel_tsl = rvb_vers_tsl(pixel);
         pixel_tsl.z *= 1.+(1.-pixel_tsl.z)*lumi_coeff;
         pixel = tsl_vers_rvb(pixel_tsl);
         }
     if(cmd == LEVEL_INPUT_LOWER_BOUND ||
        cmd == LEVEL_INPUT_UPPER_BOUND ||
        cmd == LEVEL_OUTPUT_UPPER_BOUND ||
        cmd == LEVEL_OUTPUT_LOWER_BOUND) {
         float nslh = niv_sort_lim_haute;
         float nslb = niv_sort_lim_basse;
         float nelh = niv_ent_lim_haute;
         float nelb = niv_ent_lim_basse;
 
         float m = (nslh - nslb) / (nelh - nelb);
         float p = nslh - (m*nelh);

         pixel_tsl = rvb_vers_tsl(pixel);
 
         float l = pixel_tsl.z;
 
         if(l >= nelb || l <= nelh)
           l = m * l + p;
         else if(l < nelb)
           l = nslb;
         else
           l = nslh;

         pixel_tsl.z = l;
         pixel = tsl_vers_rvb(pixel_tsl);
         }
     if(cmd == NEGATE) {
         pixel = 1.0-(pixel);
         }
     if(cmd == COLOR_BALANCE) {
         pixel.r *= float(cb_rc_coeff);
         pixel.g *= float(cb_gm_coeff);
         pixel.b *= float(cb_by_coeff);
         }

   FragColor = vec4(pixel,1.0);
}

</script>
