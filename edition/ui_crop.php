<div class="edit-module" id="edit-mod-crop" hidden=true>
<p class="centrage"> <strong> CROP </strong> </p>
<input min="1" id="crop-width">
<input min="1" id="crop-height">
<input value="0" id="crop-offset-x">
<input value="0" id="crop-offset-y">
</div>

<script>

document.getElementById("crop-width").value  = img_w;
document.getElementById("crop-height").value = img_h;
document.getElementById("crop-width").max  = img_w;
document.getElementById("crop-height").max = img_h;

function apply_cropping() {

}

</script>
