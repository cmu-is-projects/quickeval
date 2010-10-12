<?php
Partial::Render_Partial("grid", array(
	"width" => 8, 
	"title" => "Contact Us", 
	"id" => "contentblock",
	"content" => "If you have any questions, comments or feedback, please fill out this form and let us know what you're thinking!"
	)
);
?>

<form id="contactform" class="wufoo" enctype="multipart/form-data" action="/contact.php" method="post">
	
	<ul>
		<li id="fo27li1" class="leftHalf ">
			<label class="desc" id="title2" for="name">Name<span id="req_1" class="req">*</span></label>
			<div>
				<input id="field1" class="field text medium" name="name" tabindex="1" type="text" maxlength="20"/> 
			</div>	
			<p class="instruct" id="instruct"><small>e.g. John Smith</small></p>
		</li>
			
		<li id="fo27li1" class="leftHalf ">
			<label class="desc" id="title2" for="email">Email<span id="req_1" class="req">*</span></label>
			<div>
				<input id="field1" class="field text medium" name="email" tabindex="1" type="text" maxlength="30"/> 
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
