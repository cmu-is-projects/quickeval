<script type="text/javascript">
function addErrorMessageBox(where, message, errors){
	if (errors != null){
		for (var i = 0; i < errors.length; i++){
			message += errors[i] +"<br>";
		}
	}
	where.prepend("<p class='error'>"+message+"<br /></p>");
}
$(document).ready(function(){
	$("#user_name").focus();
});
$(document).ready(function(){
	$("#signup_form").submit(function(event){
		var info = "controller=profsignup_confirm&"+unescape($("#signup_form").serialize());;
		$.ajax({
			data: info,
			success: function(data, textStatus){
				if (data['success']){
					$("#signup_form_fieldset").text("User has been added and notified by email of their new account.");
				} else {
					addErrorMessageBox($("#signup_form_fieldset"), "Error Adding User:<br>", data.errors);
				}
			}
		});
		event.preventDefault();
	});
});
</script>
<form id="signup_form" method="post" action="/signup">
	<fieldset id="signup_form_fieldset" class="form">
		<?php echo Partial::Render_Partial("smallhome/errors"); ?>		
		<p>
			<label for="first">First Name:</label>
			<input name="first" id="first_name" autocomplete="no" type="text" value="<?php echo $first_name;?>" />
		</p>
		<p>
			<label for="last">Last Name:</label>
			<input name="last" id="last_name" autocomplete="no" type="text" value="<?php echo $last_name;?>" />
		</p>
		<p>
			<label for="email">E-Mail:</label>
			<input name="email" id="user_name" autocomplete="no" type="text" value="<?php echo $email;?>" />
		</p>
		<p>
			<label for="level">Access Level:</label>
			<select class="xsmall" name="level" id="level">
				<option value="<?php echo User::U_STUDENT; ?>"<?php if (isset($level) && $level == User::U_STUDENT) echo " selected"; ?>>Student</option>
				<option value="<?php echo User::U_TEACHER; ?>"<?php if (isset($level) && $level == User::U_TEACHER) echo " selected"; ?>>Teacher</option>
				<option value="<?php echo User::U_ADMIN; ?>"<?php if (isset($level) && $level == User::U_ADMIN) echo " selected"; ?>>Administrator</option>
			</select>
		</p>
		<p>
			<label for="password">Password (Leave blank to autogenerate):</label>
			<input name="password" id="password" autocomplete="no" type="text" value="" />
		</p>
		<button type="submit" class="positive" name="Submit">
			<img src="ui/images/key.png" alt="Announcement"/>Create User</button>
		</fieldset>	
</form>