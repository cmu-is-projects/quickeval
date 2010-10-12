<script type="text/javascript">
$(document).ready(function(){	
	$("#slider").easySlider({
		auto: false,
		continuous: false,
		speed: '300',
		numeric: true,
		insertAfter: false
	});
});
</script>
<h2 class="clear"><?php echo $survey->name;?> : <strong><?php echo $projectteam->name;?></strong></h2>

<?php
$numeric_responses = array();
$non_numeric_responses = array();
foreach ($responses as $result){
	if ($result["isNumeric"] == true){
		$numeric_responses[] = $result;
	} else {
		$non_numeric_responses[] = $result;
	}
}

?>


<!-- Numeric Slider -->
<?php Partial::Render_Partial("responses/instancesummary/numericslider", array("responses" => $numeric_responses)); ?>


<!-- Questions -->
<?php Partial::Render_Partial("responses/instancesummary/openended", array("responses" => $non_numeric_responses, "shownames" => $shownames)); ?>