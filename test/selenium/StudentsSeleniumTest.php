<?php
require_once 'lib/config.php';
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class StudentsSeleniumTest extends PHPUnit_Extensions_SeleniumTestCase {
	protected function setUp(){
		SCHelper::setUp($this, "fast");
	}
	public function testAddAndDeleteStudent(){		
		SCHelper::login($this);
		
		$coursename = SCHelper::uid("Test Course");
		$groupname = SCHelper::uid("Test Group");
		
		//open courses page & add course
		$this->open("/courses");
		$this->click("showadd");
		$this->type("id=coursename", $coursename);
		$this->click("id=coursesubmit");
		
        SCHelper::waitForAjax($this); 

		//go to course page so that we can add students
		$this->click("xpath=//a[text()='$coursename']");
		$this->waitForPageToLoad();
		$this->assertTextPresent($coursename);
		    
	    //add a student
	    $this->click("id=showadd");
		$this->type("id=studentlist", "testemail@mail.com,Test,Cool");
		$this->click("id=surveysubmit");  
		
		//check to see if student was added      
		$this->waitForPageToLoad();
		$this->assertTextPresent("testemail@mail.com"); 
		
		//delete group
	    $this->click("xpath=//li/img[@alt='Delete this student from this course?']");
	    $this->getConfirmation();
	    SCHelper::waitForAjax($this);
		
		//refresh to force that it isnt on the page
	    $this->refresh();
	    $this->waitForPageToLoad();
	    $this->assertTextNotPresent("testemail@mail.com");  
		$this->assertTextNotPresent("Test, Cool");
	}
}
?>