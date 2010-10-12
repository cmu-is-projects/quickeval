<div class='wufoo question'>
	<li id="<?php echo $form_name; ?>_list" class=" ">
	<label class="desc" id="<?php echo $form_name; ?>_label" for="<?php echo $form_name; ?>"><?php echo $name; ?><span class="req">*</span></label>
		<?php $count = 1; ?>
		<?php foreach ($choices as $choice){ ?>
			<input <?php if ($readonly) echo "readonly='true' disabled='true' "; ?> id="<?php echo $form_name."_$count"; ?>" name="<?php echo $form_name; ?>" type="radio" class="field radio" value="<?php echo $choice; ?>" <?php if (implode("", $response) == $choice) echo ' checked="checked"'; ?> />
			<label class="choice" for="<?php echo $form_name."_$count"; ?>"><?php echo $choice; ?></label>
		<?php $count++; ?>
		<?php } ?>
	</li>
</div>