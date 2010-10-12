<?php
if (!isset($comment)){
?>
<div class="right">
<?php echo Partial::Render_Partial("iconbutton", array("text" => "Add Comment", "icon" => "comment", "id" => "button_".$comment_id, "onclick" => "$('#comment_".$comment_id."').toggle('blind'); $(this).hide();" )); ?></div><br/>

<?php
}
?>
<div id="comment_<?php echo $comment_id; ?>">
	<textarea style="width: 100%;" class="comment" id="text_<?php echo $comment_id; ?>" name="<?php echo $comment_id; ?>"><?php echo $comment;?></textarea>
	<a class="point" id="cancelcomment_<?php echo $comment_id; ?>">Cancel Comment</a>
</div>
<?php
if (!isset($comment)){
?>
<script type="text/javascript">
	$('#comment_<?php echo $comment_id; ?>').hide();
	$('#cancelcomment_<?php echo $comment_id; ?>').click(function () {
		$('#text_<?php echo $comment_id; ?>').val("");
		$('#comment_<?php echo $comment_id; ?>').hide('');
		$('#button_<?php echo $comment_id; ?>').toggle();
	});
</script>
<?php
}
?>