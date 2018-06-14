<div class="edit-module" id="edit-mod-scale" hidden=true>
<p class="centrage"> <strong> SCALE </strong> </p>
<p class="centrage"> Width : <input type="number" min="1" id="scale-width" oninput="keep_aspect_ratio('width')"> </p>
<p class="centrage"> Height : <input type="number" min="1" id="scale-height" oninput="keep_aspect_ratio('height')"> </p>
<p class="centrage"> Keep aspect ratio
<input type="checkbox" id="scale-kar" name="Keep aspect ratio" checked>
</p>

<div class="centrage">
<button class="clickable" id="scale-reset-btn"> RESET </button>
<button class="clickable"> APPLY </button>
</div>

</div>

<script>

scale_width_field  = document.getElementById("scale-width");
scale_height_field = document.getElementById("scale-height");

scale_width_field.min  = 0;
scale_height_field.min = 0;
scale_width_field.value  = img_w;
scale_height_field.value = img_h;

var scale_reset_btn = document.getElementById("scale-reset-btn");
scale_reset_btn.onclick = reset_scale;

function keep_aspect_ratio(from_input) {

  kar = document.getElementById("scale-kar").checked;
  if(kar == false)
    return;
  else if(from_input == 'width')
    ;
  else if(from_input == 'height')
    ;
}

function apply_scaling() {

}

function reset_scale() {
  scale_width_field.min  = 0;
  scale_height_field.min = 0;
  scale_width_field.value  = img_w;
  scale_height_field.value = img_h;
}

</script>
