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
		var info = "controller=profsignup&"+unescape($("#signup_form").serialize());;
		$.ajax({
			data: info,
			success: function(data, textStatus){
				if (data['success']){
					$("#signup_form_fieldset").text("Thank you for signing up.  You will receive an email containing your username and password once your signup information has been verified!");
				} else {
					addErrorMessageBox($("#signup_form_fieldset"), "Error sending message:<br>", data.errors);
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
			<input name="first" id="first_name" autocomplete="no" type="text" value="" />
		</p>
		<p>
			<label for="last">Last Name:</label>
			<input name="last" id="last_name" autocomplete="no" type="text" value="" />
		</p>
		<p>
			<label for="email">E-Mail:</label>
			<input name="email" id="user_name" autocomplete="no" type="text" value="" />
		</p>
		<p>
			<label for="email">Confirm E-Mail:</label>
			<input name="email2" id="user_name2" autocomplete="no" type="text" value="" />
		</p>
		<p>
			<label for="school">University Name:</label>
			<input name="school" id="school_name" autocomplete="no" type="text" value="" />
		</p>     
		<p>
			<label for="phone">Phone Number:</label>
			<input name="phone" id="phone_number" autocomplete="no" type="text" value="" />
		</p>     
		<p>
			<label for="dept">Field of Study:</label> 
			<select id="depttype" name="type" class="field select xsmall"> 
				<option id="dept_type" value="" selected="selected">Please Select</option>
				<option value="Business">Business</option>
				<option value="Engineering">Engineering</option>
				<option value="Social Sciences">Social Sciences</option>
				<option value="Technology">Technology</option>
				<option value="Other">Other</option>
			</select>
		</p>
		<button type="submit" class="positive" name="Submit">
			<img src="ui/images/key.png" alt="Announcement"/>Submit</button>
		</fieldset>	
</form>