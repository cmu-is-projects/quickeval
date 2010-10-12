<script type="text/javascript">
$(document).ready(function(){
	$("#reset_password_form").submit(function(event){
		$.ajax({
			data: $("#reset_password_form").serialize(),
			success: function(data, textStatus){
				if (data['success']){
					//success
					addNoticeBox($("#resetpasswordform"), "Password was successfully reset.  <br /><a style='float:left;' href='/home'>Please Try Logging In Again</a><br />");
					window.setTimeout("window.location.href='/home'", 6000);
					$("#formguts").hide("slide", {direction:'vertical'});
				} else {
					addErrorBox($("#resetpasswordform"), "Error resetting password", data.errors);
				}
			}
		});
		event.preventDefault();
	});
});
</script>
<form id="reset_password_form">
	<input type='hidden' value='forgotpassword' name='controller' />
	<input type='hidden' value='change' name='action' />
	<input name="key" type='hidden' value="<?php echo $_REQUEST['key'];?>" />	
	<fieldset class="form" id="resetpasswordform"><div id='formguts'>
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

		<input type="submit" value="Reset Password" /></div>
	</fieldset>	
</form>