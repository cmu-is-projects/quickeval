<?php
/**
* This is the testing class for the Question class
* @package QuickEvalTest
* @author Lauren Taglieri
*/
require_once "lib/config.php";

class QuestionTest extends PHPUnit_Framework_TestCase {
	public function testParameterValidation(){
		$q = new Question();
		$this->assertFalse($q->validate(), "Question is invalid");
		$q->owner_id = -5;
		$this->assertFalse($q->validate(), "Question still invalid - owner_id < 0");
		$q->survey_id = -3;
		$this->assertFalse($q->validate(), "Question still invalid - survey_id < 0");
		$q->type = "";
		$this->assertFalse($q->validate(), "Question still invalid - type is blank");
		$q->weight = -1;
		$this->assertFalse($q->validate(), "Question still invalid - weight < 0");
		$q->data = "";
		$this->assertFalse($q->validate(), "Question still invalid - text is blank");
		$q->list_order = -5;
		$this->assertFalse($q->validate(), "Question still invalid - list_order < 0"); 
		$q->active = -6;
		$this->assertFalse($q->validate(), "Question still invalid - active must be 0 or 1");
		
		$q->owner_id = 2;
		$q->survey_id = 3;
		$q->type = 1;
		$q->weight = 1.5;           
		$qd = new QuestionData("atasd", array("asd", "asd"));
	    $q->data = base64_encode(serialize($qd)); 
		$q->list_order = 5;      
		$q->active = 1;
		$this->assertTrue($q->validate(), "Make sure that Question is valid");
		$this->assertTrue($q->save(), "Couldn't save Question");
		
		$result = $q->id;
		$otherQuestion = new Question($result);
		$this->assertTrue($otherQuestion->force_delete(), "Couldn't delete object");
		try {
			$deletedQuestion = new Question($result);
			$this->assertNull($deletedQuestion);
		} catch(Exception $e){
			
		}
	}
}
?>