<div class='wufoo question'>
	<li id="<?php echo $form_name; ?>_list">
		<label class="desc" for="<?php echo $form_name; ?>"><?php echo $name; ?><span class="req">*</span></label>
		<div>
			<input id="<?php echo $form_name; ?>" class="field text small" <?php if ($readonly) echo "readonly='true' disabled='true' "; ?> name="<?php echo $form_name; ?>" type="text"<?php if (isset($choices['maxlength']) && $choices['maxlength'] > 0) echo " maxlength=$choices[maxlength]"; ?> value="<?php echo implode("", $response); ?>" /> 
		</div>
	</li>
</div>