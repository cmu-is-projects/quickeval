<?php
/**
* This is the testing class for the Survey class
* @package QuickEvalTest
* @author Tracy O'Connor
*/
require_once "lib/config.php";

class SurveyTest extends PHPUnit_Framework_TestCase {
	public function testParameterValidation(){
		$q = new Survey();
		$this->assertFalse($q->validate(), "Survey is invalid");
		$q->owner_id = -5;
		$this->assertFalse($q->validate(), "Survey still invalid - owner_id < 0");
		$q->name = "";
		$this->assertFalse($q->validate(), "Survey still invalid - name is blank");
		$q->description = "";
		$this->assertFalse($q->validate(), "Survey still invalid - description is blank");
		$q->modified_at = "";
		$this->assertFalse($q->validate(), "Survey still invalid - modified at is blank");
		
		$q->owner_id = 2;
		$q->name = "Valid Survey Title";
		$q->description = "This is a valid description test.";
		$q->modified_at = "2009-03-19 10:21:35";
		$q->validate();
		$this->assertTrue($q->validate(), "Make sure that survey is valid");
		$this->assertTrue($q->save(), "Couldn't save Survey");
		
		$result = $q->id;
		$otherSurvey = new Survey($result);
		$this->assertTrue($otherSurvey->force_delete(), "Couldn't delete object");
		try {
			$deletedSurvey = new Survey($result);
			$this->assertNull($deletedSurvey);
		} catch(Exception $e){
			
		}
	}
}
?>