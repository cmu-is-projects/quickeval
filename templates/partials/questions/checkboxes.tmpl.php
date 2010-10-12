<div class='wufoo question'>
	<li id="<?php echo $form_name;?>">
	<label class="desc" for="<?php echo $form_name;?>"><?php echo $name; ?><span class="req">*</span></label>
		<?php $count = 1; ?>
		<?php foreach ($choices as $c){	?>
		<input <?php if ($readonly) echo "readonly='true' disabled='true' "; ?> id="<?php echo $form_name."_$count"; ?>" name="<?php echo $form_name; ?>[]" type="checkbox" class="field checkbox" value="<?php echo $c; ?>" <?php if (in_array($c, $response)) echo "checked";?>/>
			<label class="choice" for="<?php echo $form_name."_$count"; ?>"><?php echo $c; ?></label>
		<?php $count++; ?>
		<?php } ?>
	</li>
</div>