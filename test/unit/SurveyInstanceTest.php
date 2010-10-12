<?php
/**
* This is the testing class for the SurveyInstance class
* @package QuickEvalTest
* @author Tracy O'Connor
*/
require_once "lib/config.php";

class SurveyInstanceTest extends PHPUnit_Framework_TestCase {
	public function testParameterValidation(){
		$q = new SurveyInstance();
		$this->assertFalse($q->validate(), "SurveyInstance is invalid");
		$q->survey_id = -5;
		$this->assertFalse($q->validate(), "SurveyInstance still invalid - survey_id < 0");
		$q->projectteam_id = -5;
		$this->assertFalse($q->validate(), "SurveyInstance still invalid - projectteam_id < 0");
		$q->date_given = "";
		$this->assertFalse($q->validate(), "SurveyInstance still invalid - date given is blank");
		$q->closing_date = "";
		$this->assertFalse($q->validate(), "SurveyInstance still invalid - closing date is blank");
		$q->reminder_sent = "";
		$this->assertFalse($q->validate(), "SurveyInstance still invalid - reminder sent date is blank");
		$q->timestamp = "";
		$this->assertFalse($q->validate(), "SurveyInstance still invalid - timestamp is blank");
		
		$q->projectteam_id = 4;
		$q->date_given = date('Y-m-d');
		$q->closing_date = date('Y-m-d');
		$q->reminder_sent = 1;
		$q->timestamp = "2009-03-19 10:21:35";
		$q->survey_id = 2;
		$q->validate();
		$this->assertTrue($q->validate(), "Make sure that SurveyInstance is valid");
		$this->assertTrue($q->save(), "Couldn't save SurveyInstance");
		
		$result = $q->id;
		$otherSurveyInstance = new SurveyInstance($result);
		$this->assertTrue($otherSurveyInstance->force_delete(), "Couldn't delete object");
		try {
			$deletedSurveyInstance = new SurveyInstance($result);
			$this->assertNull($deletedSurveyInstance);
		} catch(Exception $e){
			
		}
	}
}
?>