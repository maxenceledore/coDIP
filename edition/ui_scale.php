<div class="edit-module" id="edit-mod-scale" hidden=true>
<p class="centrage"> <strong> SCALE </strong> </p>
<p class="centrage"> Width : <input type="number" min="1" id="scale-width" oninput="keep_aspect_ratio('width')"> </p>
<p class="centrage"> Height : <input type="number" min="1" id="scale-height" oninput="keep_aspect_ratio('height')"> </p>
<p class="centrage"> Keep aspect ratio
<input type="checkbox" id="scale-kar" name="Keep aspect ratio" checked>
</p>
</div>

<script>

document.getElementById("scale-width").min  = 0;
document.getElementById("scale-height").min = 0;
document.getElementById("scale-width").value  = img_w;
document.getElementById("scale-height").value = img_h;

function keep_aspect_ratio(from_input) {
  
  keep_aspect_ratio = document.getElementById("scale-kar").checked;
  if(keep_aspect_ratio == false)
    return;
  else if(from_input == 'width')
    ;
  else if(from_input == 'height')
    ;
}

function apply_scaling() {

}

</script>
