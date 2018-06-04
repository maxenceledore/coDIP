<div class="edit-module" id="edit-mod-gaussian-blur" hidden=true>
<p class="centrage"> <strong> GAUSSIAN BLUR </strong> </p>
<input min="1" value="1" id="kernel-width">
<input min="1" value="1" id="kernel-height">
<p class="centrage"> Selective gaussian blur
<input type="checkbox" id="gaussian-selective">
</p>
<p class="centrage"> Threshold
<input min="0" value="0" max="255" id="gaussian-thresold">
</p>
</div>

<script>

var wh_min = Math.min(img_w,img_h);

document.getElementById("kernel-width").max  = wh_min;
document.getElementById("kernel-height").max = wh_min;

function evaluate_gaussian_kernel(width, height) {

  sigma = 0.84089642;
  
}

function apply_gaussian_blur() {

}

</script>
