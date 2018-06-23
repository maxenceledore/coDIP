<div class="edit-module" id="edit-mod-crop" hidden=true>
<p class="centrage"> <strong> CROP </strong> </p>
<p class="centrage"> Width : <input type="number" min="1" id="crop-width"> </p>
<p class="centrage"> Height : <input type="number" min="1" id="crop-height"> </p> 
<p class="centrage"> X-Offset : <input type="number" value="0" id="crop-offset-x"> </p>
<p class="centrage"> Y-Offset : <input type="number" value="0" id="crop-offset-y"> </p>
</div>

<script>

document.getElementById("crop-width").value  = img_w;
document.getElementById("crop-height").value = img_h;
document.getElementById("crop-width").max  = img_w;
document.getElementById("crop-height").max = img_h;

function apply_cropping() {

}

</script>
