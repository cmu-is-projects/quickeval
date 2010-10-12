<?php

$responsepartialarray = array();
$count = 1;

foreach ($responses as $response){
	$details = array("name" => $response['name'], "responses" => $response['responses'], "shownames" => $shownames, "resultcount" => $count++, "isNumeric" => $response['isNumeric']);
	
	if ($response['isNumeric']){
		$details = array_merge($details, array("bigresponse" => $response['bigResponse']));
	}
	$responsepartialarray[] = new Partial("responses/groupresponse", $details);

	if ($response['isNumeric'] == true){
		$responsepartialarray[] = new Partial("responses/mathsummary", array(
			"resultcount" => $count++,
			"responses" => $response['bigResponse']
			));
	}	
}

Partial::Render_Partial("responses/display", array("survey" => $survey, "projectteam" => $projectteam, "responses" => $responsepartialarray, "students" => $students));

?>