<?php
/**
* This is the testing class for the ProjectTeam class
* @package QuickEvalTest
* @author sbenders
*/
require_once "lib/config.php";

class ProjectTeamTest extends PHPUnit_Framework_TestCase {
	public function testParameterValidation(){
		$a = new ProjectTeam();	
		$this->assertFalse($a->validate(), "Project Team is not valid");
		$a->owner_id = 1;
		$this->assertFalse($a->validate(), "Course Id isn't Valid");
		$a->course_id = 1;
		$this->assertFalse($a->validate(), "Name isn't Valid");
		$a->name = "Team Awesome";
		$this->assertTrue($a->validate(), "Project Team is Valid");
		$this->assertTrue($a->save(), "Couldn't save Project Team");
		
		$result = $a->id;
		$otherProjectTeam = new ProjectTeam($result);
		$this->assertTrue($otherProjectTeam->force_delete(), "Couldn't delete object");
		try {
			$deletedProjectTeam= new ProjectTeam($result);
			$this->assertNull($deletedProjectTeam);
		} catch(Exception $e){
			
		}
	}
}
?>
