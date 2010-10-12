<script type="text/javascript">
function exampleText(egtext){
	return '<p class="instruct" id="instruct"><small>e.g. '+egtext+'</small></p>';
}
function addButton(testt){
	return "<input type='button' name='addmore' id='addmore' value='"+testt+"' />";
	//return '<a class="ovalbutton" name="addmore" id="addmore" style="cursor:pointer;"><span><img src="ui/images/icons/add.png" style="float:left"/>&nbsp;'+testt+'</span></a>';
}
function questionName(){ 
	var v = $("#questiontype option:selected").val();
	if (v == 1){
	   //Rating
	   return 	'<label class="desc" for="questionname">Question<span class="req">*</span></label><div><input id="questionname" class="field text medium" name="name" type="text" /></div><p class="instruct" id="instruct"><small>e.g. This group member was reliable and completed tasks on time</small></p><div><span class="tip"><small>Tip: Type <a class="bolder">%name%</a> so that the question includes the name of the person being evaluated when your students are completing the evaluation.</small></span></div>';
	}else if (v == 2){
		//Short Answer
		return 	'<label class="desc" for="questionname">Question<span class="req">*</span></label><div><input id="questionname" class="field text medium" name="name" type="text" /></div><p class="instruct" id="instruct"><small>e.g. In what areas do you feel this person could improve?</small></p><div class="rightHalf"><span class="tip"><small>Tip: Type <a class="bolder">%name%</a> so that the question includes the name of the person being evaluated when your students are completing the evaluation.</small></span></div>';
	}else if (v == 3){
		//Multiple Choice
		return 	'<label class="desc" for="questionname">Question<span class="req">*</span></label><div><input id="questionname" class="field text medium" name="name" type="text" /></div><p class="instruct" id="instruct"><small>e.g. How would you assess this group member\'s overall contribution to the project?</small></p><div><span class="tip"><small>Tip: Type <a class="bolder">%name%</a> so that the question includes the name of the person being evaluated when your students are completing the evaluation.</small></span></div>';
	}else if (v == 4){
		//Numeric Input
		return 	'<label class="desc" for="questionname">Question<span class="req">*</span></label><div><input id="questionname" class="field text medium" name="name" type="text" /></div><p class="instruct" id="instruct"><small>e.g. How many hours per week did this person devote to the project?</small></p><div><span class="tip"><small>Tip: Type <a class="bolder">%name%</a> so that the question includes the name of the person being evaluated when your students are completing the evaluation.</small></span></div>';
	}else if (v == 5){
	   //Checkboxes
	   return 	'<label class="desc" for="questionname">Question<span class="req">*</span></label><div><input id="questionname" class="field text medium" name="name" type="text" /></div><p class="instruct" id="instruct"><small>e.g. In what areas did this person contribute to the project?</small></p><div><span class="tip"><small>Tip: Type <a class="bolder">%name%</a> so that the question includes the name of the person being evaluated when your students are completing the evaluation.</small></span></div>';
	}
}
function multipleChoiceOption(){
	return "<input class='field text' onkeypress='multipleChoiceAdd(event);' type='text' name='choices[]' /><br/>";
}
function dottedLine() {
	return "<div style='width:100%;border-bottom:dashed 1px #BEBEBE;'></div>"
}
function multipleChoiceAdd(e){
	if (e.which == 13){
		$("#addmore").before(multipleChoiceOption());
		$("#addmore").prev().focus();
		e.preventDefault();
	}
}
function resetForm(){
	$("#questionname").text("");
	$("#moredetails").text("");
	$("#defaultquestiontype").attr("selected", "true");
	
}
$(document).ready(function(){
	resetForm();
	$("#addquestionform").submit(function(event){
		var info = "controller=questions&action=add&"+unescape($("#addquestionform").serialize());
		$.ajax({
			data: info,
			success: function(data, textStatus){
				if (data['success']){
					window.location.reload();
				} else {
					addErrorBox($("#addquestionform"), "Error adding question", data.errors);
				}
			}
		});
		event.preventDefault();
	});

	$("#quickform").hide();	
	$("#surveyform").submit(function(event){
					$("#surveyname").attr("value", "");
					$("#surveydescription").attr("value", "");
					$("#showadd").toggle();
					$("#quickform").toggle('blind');
	});
	$("#questiontype").change(function(event){
		var v = $("#questiontype option:selected").val();
		$("#moredetails").empty();
		if (v != 0)
			$("#moredetails").append(questionName());
		if (v == 1){
			//Rating
			$("#moredetails").append("<label class='desc'>Rating Scale<span class='req'>*</span></label>");
			$("#moredetails").append(exampleText("<br />Not At All<br />Sometimes<br />Frequently<br />All of the Time"));
			$("#moredetails").append(multipleChoiceOption());    
			$("#moredetails").append(multipleChoiceOption()); 
			$("#moredetails").append(addButton("Add another item to rating scale"));
			$("#addmore").click(function(event){
				$("#addmore").before(multipleChoiceOption());
				$("#addmore").prev().focus();
			});
			$("#moredetails").append(dottedLine()); 
		} else if (v == 2){
			//short answer
			$("#moredetails").append(dottedLine()); 
		} else if (v == 3){
			//multiple choice
			$("#moredetails").append("<label class='desc'>Options for Multiple Choice<span class='req'>*</span></label>");
			$("#moredetails").append(exampleText("<br />Generally Inadequate<br />Insufficient/Problematic<br />Good<br />Very Good<br />Exceptional"));
			$("#moredetails").append(multipleChoiceOption()); 
			$("#moredetails").append(multipleChoiceOption());
			$("#moredetails").append("<input type='button' name='addmore' id='addmore' value='Add More Options' />");
			$("#addmore").click(function(event){
				$("#addmore").before(multipleChoiceOption());
				$("#addmore").prev().focus();
			});
			$("#moredetails").append(dottedLine()); 
		} else if (v == 4){
			//numeric input
			$("#moredetails").append(dottedLine()); 
		} else if (v == 5){
			//checkboxes
			$("#moredetails").append("<label class='desc'>Options for Checkboxes<span class='req'>*</span></label>");
			$("#moredetails").append(exampleText("<br />Programming<br />Project Management<br />Design<br />Research<br />Testing<br />"));
			$("#moredetails").append(multipleChoiceOption());
			$("#moredetails").append(multipleChoiceOption());
			$("#moredetails").append("<input type='button' name='addmore' id='addmore' value='Add More Options' />");
			$("#addmore").click(function(event){
				$("#addmore").before(multipleChoiceOption());
				$("#addmore").prev().focus();
			});
			$("#moredetails").append(dottedLine()); 
		}	
		
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


<form id="addquestionform" class="wufoo ">
	<input type='hidden' name='survey_id' value='<?php echo $survey_id;?>' />
	<ul id="quickform">		
		<li id="fo27li1" class="leftHalf ">
		<li class="leftHalf">
		<label class="desc" id="title17" for="Field17">
				What type of question?<span class="req">*</span></label>
			<div>
				<select id="questiontype" name="type" class="field select medium"> 
					<option id="defaultquestiontype" value="" selected="selected">Please Select</option>
					<option value="1">Rating</option>
					<option value="2">Short Answer</option>
					<option value="3">Multiple Choice</option>
					<option value="4">Numeric Input</option>
					<option value="5">Checkboxes</option>
				</select>
			</div>
		</li>
		<li id="moredetails" class="leftHalf desc">
			
		</li><br />
		<li>
		<?php Partial::Render_Partial("iconbutton", array("id" => "addquestionbutton", "onclick" => "$('#addquestionform').submit();", "icon" => "add", "text" => "Add")); ?> or 
		<a href="#" id="canceladd"><small>Cancel</small></a></li>
	</ul>
</form>