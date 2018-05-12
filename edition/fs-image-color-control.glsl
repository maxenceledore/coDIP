#version 300 es
precision highp float;
precision highp int;

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

uniform uint command;

#define NO_OP                      0u
#define SATURATION_CONTROL         1u
#define INTENSITY_CONTROL          2u
#define LEVEL_INPUT_LOWER_BOUND    3u
#define LEVEL_INPUT_UPPER_BOUND    4u
#define LEVEL_OUTPUT_LOWER_BOUND   5u
#define LEVEL_OUTPUT_UPPER_BOUND   6u

in      vec2      tc;
out     vec4      color;

vec3 rvb_vers_tsl (vec3 rvb) {

  // ([0-1],[0-1],[0-1])
     float R = rvb.x;
     float V = rvb.y;
     float B = rvb.z;

     float M = max(R,max(V,B));
     float m = min(R,min(V,B));
     float C = M-m; /* chroma */

     float T = 0.0f;
     float S = 0.0f;
     float L = 0.5f*(M+m);

     if(L != 1.0f)
       S = C/(1.0f-abs(2.0f*L-1.0f));

     if(C == 0.0f)
       T = 0.0f;
     else if(M == R)
       T = mod(((V-B)/C), 6.0f);

     else if(M == V)
       T = mod(((B-R)/C)+2.0, 6.0f);

     else if(M == B)
       T = mod(((R-V)/C)+4.0, 6.0f);

     T*= 60.0f;

     return vec3(T,S,L);
}

vec3 tsl_vers_rvb (vec3 tsl) {

  // ([0-360],[0-1],[0-1])
     float T = clamp(tsl.x,0.0f,360.0f);
     float S = clamp(tsl.y,0.0f,1.0f);
     float L = clamp(tsl.z,0.0f,1.0f);

     vec3 rvb = vec3(0.0f,0.0f,0.0f);

     float C = (1.0f-abs(2.0f*L-1.0f))*S;
     float X = C*(1.0-abs(mod(T/60.0f,2.0f)-1.0f));
     float m = L-0.5f*C;

//   if(T == 0.0f)
//     rvb = vec3(0.0,0.0f,0.0f);
//
//   else if((T/60.0f >= 0.0f) && (T/60.0f < 1.0f))

     if((T/60.0f >= 0.0f) && (T/60.0f < 1.0f))
rvb = vec3(C,X,0.0f);

     else if((T/60.0f >= 1.0f) && (T/60.0f < 2.0f))
    rvb = vec3(X,C,0.0f);

     else if((T/60.0f >= 2.0f) && (T/60.0f < 3.0f))
    rvb = vec3(0.0f,C,X);

     else if((T/60.0f >= 3.0f) && (T/60.0f < 4.0f))
    rvb = vec3(0.0f,X,C);

     else if((T/60.0f >= 4.0f) && (T/60.0f < 5.0f))
    rvb = vec3(X,0.0f,C);
     else
rvb = vec3(C,0.0f,X);

     rvb += vec3(m,m,m);

     return rvb;
}

vec3 highlight_frags_matching_color(vec3  tested,
    vec3  color_to_match,
    float threshold) {

     vec3 HIGHLIGHT_COLOR = vec3(0.0f,0.0f,255.0f);

     float l = length(distance(tested, color_to_match));

     if(l < threshold)
       return mix(tested, HIGHLIGHT_COLOR, 0.01f);

     return tested;
}

vec3 niveau_de_gris_tsl(vec3 rvb) {

     vec3 tsl = rvb_vers_tsl(rvb);

     return vec3(tsl.z);
}

vec3 niveau_de_gris_luminance(vec3 rvb) {

     vec3 coeff = vec3(0.2126f,0.7152f,0.0722f);

     float l = dot(rvb,coeff);

     return vec3(l);
}

void main() {

   uint cmd = command;

   vec3 pixel     = texture(img, tc).xyz;
   vec3 pixel_tsl = vec3(0.0f,0.0f,0.0f);

   switch(cmd) {
     case NO_OP:
         break;
     case SATURATION_CONTROL:
         pixel_tsl = rvb_vers_tsl(pixel);
         pixel_tsl.y *= float(satu_coeff);
         pixel = tsl_vers_rvb(pixel_tsl);
         break;
     case INTENSITY_CONTROL:
         pixel_tsl = rvb_vers_tsl(pixel);
         pixel_tsl.z *= 1.+(1.-pixel_tsl.z)*lumi_coeff;
         pixel = tsl_vers_rvb(pixel_tsl);
         break;
     case LEVEL_INPUT_LOWER_BOUND:
     case LEVEL_INPUT_UPPER_BOUND:
     case LEVEL_OUTPUT_UPPER_BOUND:
     case LEVEL_OUTPUT_LOWER_BOUND:
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
         break;
     default:
         break;
   }

   color = vec4(pixel,1.0f);
}
