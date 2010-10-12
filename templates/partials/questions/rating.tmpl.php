<div class="wufoo lifix question">
	<table>
		<tr>
			<td class="wide"></td>
			<?php foreach ($choices as $k){ ?>
				<td width="10px" align="center"><?php echo $k; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<td class="wide"><label class="desc"><?php echo $name; ?><span class="req">*</span></label></td>
			<?php foreach ($choices as $k){ ?>
				<td><input id="Field17"<?php if ($readonly) echo "readonly='true' disabled='true' "; ?> name="<?php echo $form_name; ?>" type="radio" class="radio_rating" value="<?php echo $k; ?>" <?php if (implode("", $response) == $k) echo "checked"; ?>/></td>
			<?php } ?>
		</tr>
	</table>
</div>