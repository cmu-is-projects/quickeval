<script type="text/javascript">
$(document).ready(function(){
	$.ajax({
		data: "controller=surveys&action=list",
		success: function(data, textStatus){
			if (data['success']){
				if (data.surveys != null){
					for (var i = 0; i < data.surveys.length; i++){
						new_survey_row($("#surveyform"), data.surveys[i]);
					}
				}
			}
		}
	})
})
$(document).ready(function(){
	$("#quickform").hide();	
	$("#surveyform").submit(function(event){
		var info = "controller=surveys&action=add&name="+$("#surveyname").attr("value")+"&description="+$("#surveydescription").attr("value");
		$.ajax({
			data: info,
			success: function(data, textStatus){
				if (data['success']){
					new_survey_row($("#surveyform"), data.survey);
					$("#surveyname").attr("value", "");
					$("#surveydescription").attr("value", "");
					$("#showadd").toggle();
					$("#quickform").toggle('blind');
				} else {
					addErrorBox($("#surveyform"), "Error adding survey", data.errors);
				}
			}
		});
		event.preventDefault();
	});
});
$(function() {
	$("#showadd").click(function() {
		$("#showadd").toggle();
		$("#quickform").toggle('blind');
	});	
	$("#canceladd").click(function() {
		$("#showadd").toggle();
		$("#quickform").toggle('blind');
	});
});

</script>

<form id="surveyform" class="wufoo ">
	
	<ul id="quickform">		
		<li id="fo27li1" class=" ">
		<label class="desc" id="title2" for="name">Evaluation Name<span id="req_1" class="req">*</span></label>
		<div>
			<input id="surveyname" class="field text medium" name="name" tabindex="1" type="text" maxlength="30"/> 
		</div>	
		<p class="instruct " id="instruct"><small>e.g. IS Project Evaluation</small></p></li>
		
		<li id="fo27li2" class=" ">
		<label class="desc" id="title2" for="description">Description<span id="req_1" class="req">*</span></label>
		<div>
			<textarea id="surveydescription" class="field textarea small" name="description" rows="10" cols="10"  tabindex="2"></textarea> 
		</div></li>
		
		<li>
		<input id="surveysubmit" type="submit" value="Save" tabindex="3" /> or 
		<a href="#" id="canceladd"><small>Cancel</small></a></li>
	</ul>
	
</form>