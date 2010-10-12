<script type="text/javascript">
$(document).ready(function(){
	$.ajax({
		data: "controller=courses&action=list",
		success: function(data, textStatus){
			if (data['success']){
				if (data.courses != null){
					for (var i = 0; i < data.courses.length; i++){
						new_course_row($("#courseform"), data.courses[i]);
					}
				}
			}
		}
	})
});
$(document).ready(function(){
	$("#quickform").hide();	
	$("#courseform").submit(function(event){
		var info = "controller=courses&action=add&name="+$("#coursename").attr("value");
		$.ajax({
			data: info,
			success: function(data, textStatus){
				if (data['success']){
					new_course_row($("#courseform"), data.course);
					$("#coursename").attr("value", "");
					$("#quickform").toggle('blind');
					$("#showadd").toggle();		
				} else {
					addErrorBox($("#courseform"), "Error adding course", data.errors);
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
<form id="courseform" class="wufoo ">
	
	<ul id="quickform">		
		<li id="fo27li2" >
		<label class="desc" id="title2" for="name">Course Name<span id="req_1" class="req">*</span></label>
		<div>
			<input id="coursename" class="field text medium" name="name"  type="text" tabindex="1" maxlength="30"/> 
		</div>	
		<p class="instruct " id="instruct"><small>e.g. 36-101 Fall 2008</small></p></li>
		
		<li>
		<input id="coursesubmit" type="submit" class="btTxt" value="Add Course" tabindex="2"/> or
		<a href="#" id="canceladd">Cancel</a></li>
	
	</ul>
	
</form>