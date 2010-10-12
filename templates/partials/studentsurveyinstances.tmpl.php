<script type="text/javascript">
$(document).ready(function(){
	$(".legend").hide();
	$(".legendclick").click(function() { 
		$(".legend").toggle('blind');
	});
	$(".classlist .classarea:gt(0)").hide();
	$(".classlist .classhead:gt(0)").removeClass("open");	
	$(".classhead").click(function() {
		$(this).next(".classarea").slideToggle(500);
		$(this).toggleClass("open");
		return false;
	});
});
</script>

<ol class="classlist">
<?php if (isset($evalgroups)){ 
	foreach ($evalgroups as $evalgroup){
		Partial::Render_Partial("studentsurveyinstancerow", array(
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
<div class="right aright">
<h3><a class="legendclick point">Legend</a></h3>
<div class="legend">
Incomplete Survey <img src="ui/images/open-incomplete.png" alt="open incomplete" /><br/>
Completed Survey (still open) <img src="ui/images/open-complete.png" alt"open complete" /><br/>
Completed Survey (closed) <img src="ui/images/closed-complete.png" alt"closed complete" /><br/>
Survey Not Open <img src="ui/images/closed-incomplete.png" alt"closed incomplete" /><br/>
</div></div><br/>