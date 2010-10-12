<script type="text/javascript">
$(document).ready(function(){
	$("#signup_form").submit(function(event){
		$.ajax({
			data: $("#signup_form").serialize(),
			success: function(data, textStatus){
				if (data['success']){
					//success
					window.location.href = "/home";
				} else {
					addErrorBox($("#signup_form_content"), "Error Signing Up", data.errors);
				}
			}
		});
		event.preventDefault();
	});
});
</script>
<form id="signup_form">
	<input type='hidden' value='students' name='controller' />
	<input type='hidden' value='validate' name='action' />
	<input name="key" type='hidden' value="<?php echo $_GET['key'];?>" />	
	<fieldset class="form" id="signup_form_content">
		<?php echo Partial::Render_Partial("smallhome/errors"); ?>		
		<p>
			<label for="email">E-Mail:</label>
			<input name="email" id="email" autocomplete="no" type="text" value="<?php echo $_GET['email'];?>" />
		</p>
		<p>
			<label for="password">Password:</label>
			<input name="pass1" id="password" type="password" />
		</p>
		<p>
			<label for="passwordverify">Verify Password:</label>
			<input name="pass2" id="passwordverify" type="password" />
		</p>

		<button type="submit" class="positive" name="Submit">
			<img src="ui/images/key.png" alt="Announcement"/>Sign Up</button>
	</fieldset>	
</form>