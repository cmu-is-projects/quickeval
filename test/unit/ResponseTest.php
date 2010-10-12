<?php
/**
* This is the testing class for the Response class
* @package QuickEvalTest
* @author Lauren Taglieri
*/
require_once "lib/config.php";

class ResponseTest extends PHPUnit_Framework_TestCase {
	public function testParameterValidation(){
		$r = new Response();
		$this->assertFalse($r->validate(), "Response is invalid");
		$r->user_id = -2;
		$this->assertFalse($r->validate(), "Response still invalid - user_id < 0");
		$r->survey_instance_id = -1;
		$this->assertFalse($r->validate(), "Response still invalid - survey_instance_id < 0");
		$r->question_id = -1;
		$this->assertFalse($r->validate(), "Response still invalid - question_id < 0");
		$r->user_for = -1;
		$this->assertFalse($r->validate(), "Response still invalid - user_for < 0");
		$r->value = "";
		$this->assertFalse($r->validate(), "Response still invalid - value is blank");
		$r->timestamp = "";
		$this->assertFalse($r->validate(), "Response still invalid - timestamp is blank");
		$r->active = 3;
		$this->assertFalse($r->validate(), "Response still invalid - active is not 0 or 1");
		
		$r->survey_instance_id = 1;
		$r->question_id = 2;
		$r->user_for = 3;
		$r->user_id = 2;
		$r->value = "good work!";
		$r->timestamp = "2009-03-19 10:21:35";
		$r->active = 1;
		$r->validate();
		$this->assertTrue($r->validate(), "Make sure that Response is valid");
		$this->assertTrue($r->save(), "Couldn't save Response");
		
		$result = $r->id;
		$otherResponse = new Response($result);
		$this->assertTrue($otherResponse->force_delete(), "Couldn't delete object");
		try {
			$deletedResponse = new Response($result);
			$this->assertNull($deletedResponse);
		} catch(Exception $e){
			
		}
	}
}
?>