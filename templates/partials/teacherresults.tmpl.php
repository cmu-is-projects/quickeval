<script type="text/javascript">
$(document).ready(function(){
	$("#sendeval").hide();	

	$('a[rel*=facebox]').facebox();
	
	$(".classlist .classarea:gt(0)").hide();
	$(".classlist .classhead:gt(0)").removeClass("open");	
	$(".classlist .classarea:first").find('img.studentdots').each(function(){
		fetchSurveyResponseString($(this).attr("id"));
	});
	$(".classhead").click(function() {
		$(this).next(".classarea").slideToggle(500);
		$(this).toggleClass("open");
		
		//trigger events for the status icons
		$(this).next(".classarea").find('img.studentdots').each(function(){
			fetchSurveyResponseString($(this).attr("id"));
		});
				
		return false;
	});
});
function extendDeadline(groupid, instanceid){
	if (confirm("Are you sure you want to 24 hours from now?")){
		$.ajax({
			data: "controller=surveyinstances&action=extenddeadline&group_id="+groupid+"&instance_id="+instanceid,
			success: function(data, textStatus){
				if (data['success']){
					$("#duedate_"+instanceid+"_"+groupid).text(data['newdate']);
					alert("Deadline was extended");
				} else {
					alert("There was an error extending that deadline");
				}
			}
		});
	} else {
		alert("Deadline was not extended");
	}
	
}
function fetchSurveyResponseString(str){
	var parts = str.split("_");	
	return fetchSurveyResponse(parts[2],parts[4]);	
}
function fetchSurveyResponse(instance_id, student_id){
	if ($("#completeall_si_"+instance_id+"_s_"+student_id).attr("src") != "ui/images/completionloader.gif") 
		return; //we already have the result
	
	$.ajax({
		data: "controller=surveyinstances&action=completed_all&si_id="+instance_id+"&s_id="+student_id,
		success: function(data, textStatus){
			if (data['success'])
				$("#completeall_si_"+instance_id+"_s_"+student_id).attr("src",data['link']);			
			
		}
	});
	
}

</script>
<ol class="classlist">
<?php if (isset($evalgroups)){ 
	foreach ($evalgroups as $evalgroup){
		Partial::Render_Partial("teachersurveyinstancerow", array(
			"course" => $evalgroup['course'],
			"group" => $evalgroup['group'],
			"students" => $evalgroup['students'],
			"instances" => $evalgroup['instances']
			)
		);
	}
}
?>
</ol>
<br/>
<div class="left">
	<h3>Legend</h3>
	<div class="legend">
		<div class="legendcol">
			<img src="ui/images/open-incomplete.png" alt="open incomplete" /> Incomplete Survey<br/>
			<img src="ui/images/open-complete.png" alt="open complete" /> Completed Survey (still open)
		</div>
		<div class="legendcol">
			<img src="ui/images/closed-complete.png" alt="closed complete" /> Completed Survey (closed)<br/>
			<img src="ui/images/closed-incomplete.png" alt="closed incomplete" /> Survey Not Open
		</div>
		<div class="legendcol">
			<img src="ui/images/viewresultssummary.png" alt="view results summary" /> View Results Summary<br/>
			<img src="ui/images/viewresults.png" alt="view results" /> View Results
		</div>
		<div class="legendcol">
			<img src="ui/images/csv.png" alt="export results" /> Export Results to CSV<br/>
			<img src="ui/images/extend.png" alt="extend deadline" /> Extend Deadline 24 hours
		</div>
		&nbsp;
	</div>
</div>

<div id="sendeval">
<?php Partial::Render_Partial("forms/sendevaluation", array()); ?>
</div>