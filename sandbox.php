<?php
include("lib/config.php");
die(print_r(Text::querystring_to_hash("http://localhost:8888/sada.php?course=123")));


$myquestion = new QuestionData("How old are you?", array("maxlength" => 3));
$ques = new Question();
$ques->owner_id = 1;
$ques->survey_id = 182;
$ques->type = Question::Q_NUMERICINPUT;
$ques->data = serialize($myquestion);

if ($ques->save()){
	echo "$ques->id";
} else {
	echo print_r($ques->get_errors());
}




?>
