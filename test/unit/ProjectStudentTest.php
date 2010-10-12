<?php
/**
* This is the testing class for the ProjectStudent class
* @package QuickEvalTest
* @author sbenders
*/
require_once "lib/config.php";

class ProjectStudentTest extends PHPUnit_Framework_TestCase {
	public function testParameterValidation(){
		$a = new ProjectStudent();	
		$this->assertFalse($a->validate(), "Project Student is not valid");
		$a->user_id = 1;
		$this->assertFalse($a->validate(), "Project Team ID isn't Valid");
		$a->projectteam_id = 1;
		$this->assertTrue($a->validate(), "Project Student is Valid");
		$this->assertTrue($a->save(), "Couldn't save Project Team");
		
		$result = $a->id;
		$otherProjectStudent= new ProjectStudent($result);
		$this->assertTrue($otherProjectStudent->force_delete(), "Couldn't delete object");
		try {
			$deletedProjectStudent= new ProjectStudent($result);
			$this->assertNull($deletedProjectStudent);
		} catch(Exception $e){
			
		}
	}
}
?>
