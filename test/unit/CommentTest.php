<?php
/**
* This is the testing class for the Comments class
* @package QuickEvalTest
* @author Lauren Taglieri
*/
require_once "lib/config.php";

class CommentTest extends PHPUnit_Framework_TestCase {
	public function testParameterValidation(){
		$c = new Comment();
		$this->assertFalse($c->validate(), "Comment is invalid");
		$c->response_id = -15;
		$this->assertFalse($c->validate(), "Comment still invalid - response_id < 0");
		$c->detail = "";
		$this->assertFalse($c->validate(), "Comment still invalid - detail is blank");

		$c->response_id = 2;
		$c->detail = "good!";
		$c->validate();
		$this->assertTrue($c->validate(), "Make sure that comment is valid");
		$this->assertTrue($c->save(), "Couldn't save Comment");
		
		$result = $c->id;
		$otherComment = new Comment($result);
		$this->assertTrue($otherComment->force_delete(), "Couldn't delete object");
		try {
			$deletedComment = new Comment($result);
			$this->assertNull($deletedComment);
		} catch(Exception $e){
			
		}
	}
}
?>