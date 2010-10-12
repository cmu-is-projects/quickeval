<?php
/**
* This is the testing class for the Course class
* @package QuickEvalTest
* @author Lauren Taglieri
*/
require_once "lib/config.php";

class CourseTest extends PHPUnit_Framework_TestCase {
	public function testParameterValidation(){
		$co = new Course();
		$this->assertFalse($co->validate(), "Course is invalid");
		$co->owner_id = -2;
		$this->assertFalse($co->validate(), "Course still invalid - owner_id < 0 and not -1");
		$co->name = "";
		$this->assertFalse($co->validate(), "Course still invalid - name is blank");
		$co->active = 3;
		$this->assertFalse($co->validate(), "Course still invalid - active is not 0 or 1");
		
		$co->owner_id = 1;
		$co->name = "course1";
		$co->active = 1;
		$co->validate();
		$this->assertTrue($co->validate(), "Make sure that Course is valid");
		$this->assertTrue($co->save(), "Couldn't save Course");
		
		$result = $co->id;
		$otherCourse = new Course($result);	
		$this->assertTrue($otherCourse->force_delete(), "Couldn't delete object");
		try {
			$deletedCourse = new Course($result);
			$this->assertNull($deletedCourse);
		} catch(Exception $e){
			
		}
	}
}
?>