<script type="text/javascript">
$(document).ready(function(){
	$("#mysettingsform").submit(function(event){
		$.ajax({
			data: $("#mysettingsform").serialize(),
			success: function(data, textStatus){
				if (data['success']){
					addNoticeBox($("#mysettingsform"), "Successfully updated your settings");
					$("#oldpass").attr("value","");
					$("#newpw1").attr("value","");
					$("#newpw2").attr("value","");
					
				} else {
					addErrorBox($("#mysettingsform"), "Error updating your settings", data.errors);
				}
			}
		});
		event.preventDefault();
	});
});

</script>
<form class="wufoo " id="mysettingsform" enctype="multipart/form-data" action="/mysettings.php" method="POST">
	<input type="hidden" name="controller" value="mysettings" />
	<ul>		
		<li class="leftHalf "><label class="desc" id="title2">Avatar</label><a href="http://en.gravatar.com/emails/"><img src="<?php echo Gravatar::getGravatarImageLocation($_SESSION['current_user']->email, 60); ?>" alt="Avatar" style="margin-bottom: 2px;" /></a>
		<p class="instruct " id="instruct2"><small>Click to change or upload your avatar using Gravatar</small></p></li>
		
		<li id="fo27li2" class="rightHalf   " >
		<label class="desc" id="title2" for="Field2">Email Address</label>
		<div>
			<input id="Field2" 
			class="field text medium"
			name="email" readonly="true" disabled="true"  
			tabindex="2"
			type="text" maxlength="30" value="<?php echo $_SESSION['current_user']->email; ?>" /> 
		</div>	
		<p class="instruct " id="instruct2"><small>Contact your teacher if you wish to change your email!</small></p></li>
			
		<li id="foli0" class="    ">
			<label class="desc" id="title0" for="fname">Name</label>
			<span>
				<input id="fname" name="fname" type="text" class="field text" 
				value="<?php echo $_SESSION['current_user']->fname; ?>" size="10" tabindex="1"/>
				<label for="Field0">First</label>
			</span>
			<span>
				<input id="lname" name="lname" type="text" class="field text" 
				value="<?php echo $_SESSION['current_user']->lname; ?>" size="12" tabindex="1"/>
				<label for="Field1">Last</label>
			</span>
		</li>

		<li class="   " id="fo27li1" >
		<label id="title1" class="desc" for="oldpass">Old Password</label>
		<div>
			<input id="oldpass" 
			class="field text medium"
			name="oldpassword" type="password" maxlength="12"
			tabindex="2"  />
		</div>		</li>
		
		<li class="   " id="fo27li1" >
		<label id="title1" class="desc" for="newpassword">New Password</label>
		<div>
			<input id="newpw1" 
			class="field text medium"
			name="newpassword" type="password" maxlength="12"
			tabindex="2"  />
		</div>		
		<p class="instruct " id="instruct2"><small>Must be at least 6 letters</small></p></li>
		
		<li class="   " id="fo27li1" >
		<label id="title1" class="desc" for="newpasswordverify">Verify New Password</label>
		<div>
			<input id="newpw2" 
			class="field text medium"
			name="newpasswordverify" type="password" maxlength="12"
			tabindex="2"  />
		</div></li>

		<li class="buttons">
				<input id="saveForm" class="btTxt" type="submit" tabindex="3" value="Submit" />
				</li>
		</ul>
		
</form>