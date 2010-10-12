<?php
/**
* This is the testing class for the University class
* @package QuickEvalTest
* @author sbenders
*/
require_once "lib/config.php";

class UniversityTest extends PHPUnit_Framework_TestCase {
	public function testParameterValidation(){
		$a = new University();	
		$this->assertFalse($a->validate(), "University is not valid");
		$a->name = "CMU";
		$this->assertTrue($a->validate(), "University is Valid");
		$this->assertTrue($a->save(), "Couldn't save University");
		
		$result = $a->id;
		$otherUniversity = new University($result);
		$this->assertTrue($otherUniversity->force_delete(), "Couldn't delete object");
		try {
			$deletedUniversity= new University($result);
			$this->assertNull($deletedUniversity);
		} catch(Exception $e){
			
		}
	}
}
?>
