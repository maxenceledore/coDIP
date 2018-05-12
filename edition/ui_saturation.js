var curseur_satu = document.getElementById("satu");
curseur_satu.oninput = function() {
  const COEFF_INTERNAL_MIN = 0.0;
  const COEFF_INTERNAL_MAX = 2.0;
  const UI_SATU_VAL_MIN = curseur_satu.min;
  const UI_SATU_VAL_MAX = curseur_satu.max;
  const m = (COEFF_INTERNAL_MAX-COEFF_INTERNAL_MIN)/(UI_SATU_VAL_MAX-UI_SATU_VAL_MIN);
  const p = COEFF_INTERNAL_MAX - m*UI_SATU_VAL_MAX;
  const coeff = m*curseur_satu.value+p;
  gl.uniform1f(u_satu_coeff, coeff);
  gl.uniform1ui(u_command, SATURATION_CONTROL);
  gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
}
