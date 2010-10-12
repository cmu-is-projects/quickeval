<div class='wufoo question'>
	<li id="<?php echo $form_name;?>_list">
		<label class="desc" id="<?php echo $form_name;?>_label" for="<?php echo $form_name;?>"><?php echo $name; ?><span id="req_1" class="req">*</span></label>
		<div>
			<textarea id="<?php echo $form_name;?>" class="field textarea small" <?php if ($readonly) echo "readonly='true' disabled='true' "; ?> name="<?php echo $form_name; ?>" rows="10" cols="10"><?php echo implode("", $response); ?></textarea>
		</div>
	</li>
</div>