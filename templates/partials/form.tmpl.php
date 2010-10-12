<form class="wufoo " enctype="multipart/form-data" action="" method="POST">

	<ul>

		<li id="foli0" class="leftHalf ">
			<label class="desc" id="title0" for="fname">Name </label>
			<span>
				<input id="Field0" name="fname" type="text" class="field text" 
				value="<?php echo $_SESSION['current_user']->fname; ?>" size="10" tabindex="1"/>
				<label for="Field0">First</label>
			</span>
			<span>
				<input id="Field1" name="lname" type="text" class="field text" 
				value="<?php echo $_SESSION['current_user']->lname; ?>" size="12" tabindex="1"/>
				<label for="Field1">Last</label>
			</span>
			</li>
		
			<li id="foli2" class="rightHalf	">
			<label class="desc" id="title1" for="date" >Date of Birth</label>
			<div>
				<input type="text" id="datepicker" name="date" class="field text" tabindex="2" size="12" />
				<script type="text/javascript">
					$(function() {
						$("#datepicker").datepicker({showOn: 'button', buttonImage: 'images/calendar.gif', buttonImageOnly: true});
					});
					</script>
			</div>
			</li>
			
		<li class="leftHalf   " id="fo27li1" >
		<label id="title1" class="desc" for="oldpassword">New Password</label>
		<div>
			<input id="Field1" 
			class="field text medium"
			name="oldpassword" type="password" maxlength="12"
			tabindex="5"  />
		</div>		</li>
		
		<li class="rightHalf  " id="fo27li1" >
		<label id="title1" class="desc" for="oldpassword">Verify Password</label>
		<div>
			<input id="Field1" 
			class="field text medium"
			name="oldpassword" type="password" maxlength="12"
			tabindex="5"  />
		</div>		</li>
			
		<li class="   " id="fo27li1" >
		<label class="desc" for="love" >How much I love Noah:</label>
		<div>
			<div id="slider" style="padding: 0px; bottom: -.3em;"></div>
				<script type="text/javascript">
					$(function() {
						$("#slider").slider();
					});
				</script>
		</div></li>
			
		<li class="buttons">
			<input id="saveForm" class="btTxt" type="submit" tabindex="3" value="Submit" />
		</li>
		</ul>
</form>