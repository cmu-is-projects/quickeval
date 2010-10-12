<?php $idstr = "${controller}_${action}_${id}_"; ?>
<form id="<?php echo $idstr;?>_form" class="liveedit">
	<input type="hidden" name="controller" value="<?php echo $controller; ?>" />
	<input type="hidden" name="action" value="<?php echo $action; ?>" />
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<div id="<?php echo $idstr;?>_inputform" class="hidden">
		<input type="input" class="field textarea medium liveedit_editing" id="<?php echo $idstr;?>_inputbox" name="value" value="<?php echo $value; ?>" />
		<input type="submit" id="<?php echo $idstr;?>_save" value="Save" class="liveedit_savebutton">
		<input type="button" id="<?php echo $idstr;?>_cancel" value="Cancel" class="liveedit_cancelbutton">
	</div>
	<div id="<?php echo $idstr;?>_activate" class="liveedit_activate"><?php echo $value; ?></div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$("#<?php echo $idstr;?>_form").submit(function(event){
		var origText = $("#<?php echo $idstr;?>_activate").text();
		$("#<?php echo $idstr;?>_activate").text("saving...");
		$.ajax({
			data: $("#<?php echo $idstr;?>_form").serialize(),
			success: function(data, textStatus){
				if (data['success']){
					$("#<?php echo $idstr;?>_activate").text(data.value);
					$("#<?php echo $idstr;?>_activate").toggleClass("hidden");
					$("#<?php echo $idstr;?>_inputform").toggleClass("hidden");
				} else {
					addErrorBox($("#<?php echo $idstr;?>_form"), "Error updating", data.errors);
					$("#<?php echo $idstr;?>_activate").text(origText);
				}
			}
		});
		event.preventDefault();
	});
	$("#<?php echo $idstr;?>_activate").click(function(event){
		$("#<?php echo $idstr;?>_inputform").toggleClass("hidden");
		$("#<?php echo $idstr;?>_activate").toggleClass("hidden");
		$("#<?php echo $idstr;?>_inputbox").focus();
	});
	$("#<?php echo $idstr;?>_cancel").click(function(event){
		$("#<?php echo $idstr;?>_inputform").toggleClass("hidden");
		$("#<?php echo $idstr;?>_activate").toggleClass("hidden");
	});
});

</script>