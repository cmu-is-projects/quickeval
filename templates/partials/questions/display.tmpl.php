<script type="text/javascript">
$(document).ready(function(){
	$("#surveyresponse").submit(function(event){
		$.ajax({
			data: "controller=surveyinstances&action=submit&"+$("#surveyresponse").serialize(),
			success: function(data, textStatus){
				if (data['success']){
					window.location.href="/evaluations";
				} else {
					addErrorBox($("#surveyresponse"), "Error completing survey", data.errors);
				}
			}
		});
		event.preventDefault();
	});
});
</script>
<form id="surveyresponse">
	<input type="hidden" name="u_id" value="<?php echo $user_for->id;?>" />
	<input type="hidden" name="si_id" value="<?php echo $instance_id;?>" />
<?php
if ($questions){
   foreach ($questions as $k=>$q){ ?>
	<?php echo $q;?>
	<?php echo Partial::Render_Partial("questions/comment", array("comment" => $comments["c_".substr($k, 2)], "comment_id" => "c_".substr($k, 2))); ?>
	<br/><div class="hr">&nbsp;</div><br/>
	<?php }
} ?>
<input type="submit" class="ovalbutton" id="submitsurveyresponse" value="Complete Survey for <?php echo $user_for->name();?>" />

</form>