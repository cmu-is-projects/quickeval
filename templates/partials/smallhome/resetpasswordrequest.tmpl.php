<script type="text/javascript">
$(document).ready(function(){
	$("#reset_password_request").submit(function(event){
		$.ajax({
			data: $("#reset_password_request").serialize(),
			success: function(data, textStatus){
				if (data['success']){
					//success
					addNoticeBox($("#resetpasswordcontent"), "Successfully sent reset email");
				} else {
					addErrorBox($("#resetpasswordcontent"), "Error Resetting Password", data.errors);
				}
			}
		});
		event.preventDefault();
	});
});
</script>
<form id="reset_password_request">
	<input type='hidden' value='forgotpassword' name='controller' />
	<input type='hidden' value='initiate' name='action' />
	<fieldset class="form" id="resetpasswordcontent">
		<p>
			<label for="email">E-Mail:</label>
			<input name="email" id="email" autocomplete="no" type="text" />
		</p>
		<button type="submit" class="positive" name="Submit"><img src="ui/images/key.png" alt="Announcement"/>Send Recovery Email</button>
	</fieldset>	
</form>