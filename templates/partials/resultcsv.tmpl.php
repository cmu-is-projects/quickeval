<?php


//get horizontal across names
$horizontal_names = null;
$NEWLINE = "\r\n";
$CSV_SEPARATOR = ",";
$question = 1;

foreach ($results as $result){
	$question_number = $question++;	
	//line 1
	echo Text::cleanStringForCSV("Question ".$question_number) . $CSV_SEPARATOR;
	echo Text::cleanStringForCSV($result['name']).$NEWLINE;
	
	
	//horizontal names - only do once
	if ($horizontal_names == null){
		$horizontal_names = array();
		if (!isset($result['responses'])) die("No responses for question");
		foreach ($result['responses'][0]->responses as $participating_users){
			$horizontal_names[] = $participating_users->user_from->name();
		}
	}

	//line 2 - blank spot, names for

	foreach ($horizontal_names as $name){
		echo $CSV_SEPARATOR.Text::cleanStringForCSV($name . " Said");
	}
	echo $NEWLINE;
	
	foreach ($result['responses'] as $response){
		echo Text::cleanStringForCSV("About " . $response->user_for->name());
		foreach ($response->responses as $subresponse){
			if ($response->user_for->id == $subresponse->user_from->id){
				//we are ourselves - leave it blank
				echo $CSV_SEPARATOR;
			} else {
				echo $CSV_SEPARATOR . Text::cleanStringForCSV($subresponse->getString());
			}
		}
		echo $NEWLINE;
		
	}
	echo $NEWLINE;
}
?>