<script type="text/javascript">
$(document).ready(function(){
	$("#user_name").focus();
});
</script>
<form id="login_form" method="post" action="/login">
	<fieldset class="form">
		<?php echo Partial::Render_Partial("smallhome/errors"); ?>
		<?php if (isset($from)) {?>
			<input type='hidden' name='from' value="<?php echo $from; ?>" />
		<?php } ?>
		<p>
			<label for="email">E-Mail:</label>
			<input name="email" id="user_name" autocomplete="no" type="text" value="" />
		</p>
		<p>
			<label for="password">Password:</label>
			<input name="password" id="user_password" type="password" />
		</p>
		<button type="submit" class="positive" name="Submit">
			<img src="ui/images/key.png" alt="Announcement"/>Login</button>
			<div id="forgottenpassword">&nbsp;&nbsp; | &nbsp;&nbsp;<a href="/forgotpassword">Forgot Password?</a></div>   
			<br>
		</fieldset>	
</form>