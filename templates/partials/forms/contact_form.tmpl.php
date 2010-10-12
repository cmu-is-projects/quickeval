
<script type="text/javascript">
$(document).ready(function(){
	$("#contactform").submit(function(event){
		var info = "controller=contact&"+unescape($("#contactform").serialize());;
		$.ajax({
			data: info,
			success: function(data, textStatus){
				if (data['success']){
					addNoticeBox($("#contactform"), "Message Successfully Sent");
					$("#message").text("");
				} else {
					addErrorBox($("#contactform"), "Error sending message", data.errors);
				}
			}
		});
		event.preventDefault();
	});
});
</script>
<form id="contactform" class="wufoo">
	<div>If you have any questions, comments or feedback, please fill out this form and let us know what you're thinking!</div>

	<ul>
		<li id="fo27li1" class="leftHalf ">
			<label class="desc" id="title2" for="name">Name<span id="req_1" class="req">*</span></label>
			<div>
				<input id="field1" class="field text medium" name="name" tabindex="1" type="text" maxlength="20" <?php
				if (User::logged_in()){
					echo " value=\"".$_SESSION['current_user']->name()."\"";
				}
				?> /> 
			</div>	
			<p class="instruct" id="instruct"><small>e.g. John Smith</small></p>
		</li>
			
		<li id="fo27li1" class="leftHalf ">
			<label class="desc" id="title2" for="email">Email<span id="req_1" class="req">*</span></label>
			<div>
				<input id="field1" class="field text medium" name="email" tabindex="1" type="text" maxlength="30" <?php
				if (User::logged_in()){
					echo " value=\"".$_SESSION['current_user']->email."\"";
				}
				?>/> 
			</div>	
			<p class="instruct" id="instruct"><small>e.g. jsmith@mail.com</small></p>
		</li>
		
		<li id="fo27li1" class="leftHalf "
			<label class="desc" id="title2" for="message">Message</label>
			<div>
				<textarea id="message" class="field textarea small" name="message" tabindex="2"></textarea>
			</div>
			<p class="instruct" id="instruct"><small>Send us your requests, comments or feedback!</small></p></li>
		</li>
		
		<li class="leftHalf">
			<input id="submit" type="submit" class="btTxt" value="Send" tabindex="3" />
		</li>
	</ul>
</form>
