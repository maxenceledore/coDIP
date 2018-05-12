// TEST GAMMA sRGB
//  vec3 flux_entree = vec3(128./255.,128./255.,128./255.);
//  flux_entree = pow(flux_entree, vec3(1.0/2.2));
//  flux_sortie = pow(flux_entree, vec3(2.2));
//  color = vec4(flux_sortie,1.0f);

// TEST INVARIANCE RVB->TSL->RVB
//  vec3 flux_entree = vec3(64./255.,214./255.,187./255.);
//  vec3 flux_sortie = tsl_vers_rvb(rvb_vers_tsl(flux_entree));
//  color = vec4(flux_sortie,1.0f);

// TEST SELECTION PAR COULEUR
//  vec3 pixel = texture(img, tc).xyz;
//  pixel = highlight_frags_matching_color
//    (pixel,
//     vec3(112./255., 160./255., 160./255.),
//     56.0/255.);
//  color = vec4(pixel,1.0f);

// TEST GRIS-TSL
//  vec3 pixel = texture(img, tc).xyz;
//  pixel = niveau_de_gris_tsl(pixel);
//  color = vec4(pixel,1.0f);

// TEST GRIS-LUMINANCE
//   vec3 pixel = texture(img, tc).xyz;
//   pixel = niveau_de_gris_luminance(pixel);
//   color = vec4(pixel,1.0f);

// TEST MODULATION-SATURATION
//   vec3 pixel = texture(img, tc).xyz;
//   vec3 pixel_tsl = rvb_vers_tsl(pixel);
//   pixel_tsl.y *= float(satu_coeff);
//   pixel = tsl_vers_rvb(pixel_tsl);
//   color = vec4(pixel,1.0f);

// TEST MODULATION-LUMINOSITE
//   vec3 pixel = texture(img, tc).xyz;
//   vec3 pixel_tsl = rvb_vers_tsl(pixel);
//   pixel_tsl.z *= 1.+(1.-pixel_tsl.z)*lumi_coeff;
//   pixel = tsl_vers_rvb(pixel_tsl);
//   color = vec4(pixel,1.0f);

// TEST MODULATION-LUMINOSITE
//    vec3 pixel = texture(img, tc).xyz;
//    vec3 pixel_tsl = rvb_vers_tsl(pixel);
//    pixel_tsl.z *= 1.+pow(1.-pixel_tsl.z,3.0)*lumi_coeff;
//    pixel = tsl_vers_rvb(pixel_tsl);
//    color = vec4(pixel,1.0f);

// TEST NIVEAUX-PAR-CURSEURS
//    float nslh = niv_sort_lim_haute;
//    float nslb = niv_sort_lim_basse;
//    float nelh = niv_ent_lim_haute;
//    float nelb = niv_ent_lim_basse;
// 
//    float m = (nslh - nslb) / (nelh - nelb);
//    float p = nslh - (m*nelh);
//    
//    vec3 pixel = texture(img, tc).xyz;
//    vec3 pixel_tsl = rvb_vers_tsl(pixel);
// 
//    float l = pixel_tsl.z;
// 
//    if(l >= nelb || l <= nelh)
//       l = m * l + p;
//    else if(l < nelb)
//       l = nslb;
//    else
//      l = nslh;
// 
//    pixel_tsl.z = l;
//    pixel = tsl_vers_rvb(pixel_tsl);
//    color = vec4(pixel,1.0f); 
