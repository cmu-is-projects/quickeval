<?php
require_once 'lib/config.php';
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class CoursesSeleniumTest extends PHPUnit_Extensions_SeleniumTestCase {
	protected function setUp(){
		SCHelper::setUp($this, "fast");
	}
	public function testNotLoggedInDenied(){
		SCHelper::logout($this);
		$this->open("/courses");
		$this->assertTextPresent("You must be a teacher to access the courses page");
	}
	public function testCreateAndDeleteCourse(){		
		SCHelper::login($this);
		
		$coursename = SCHelper::uid("Test Course");
		
		//open courses page
		$this->open("/courses");
		
		//click on the show add button
		$this->click("id=showadd");
		
		//submits the new course
		$this->type("id=coursename", $coursename);
		$this->click("id=coursesubmit");
		
		//check to see if the ajax add worked   
		SCHelper::waitForAjax($this); 
		$this->assertTextPresent($coursename);     

		//delete course
		$this->click("xpath=//a[text()='$coursename']/../img[@name='delete']");
		$this->getConfirmation();
		SCHelper::waitForAjax($this);
		
		//refresh to force that it isnt on the page
		$this->refresh();
		$this->waitForPageToLoad();
		$this->assertTextNotPresent($coursename);
	}
}
?>