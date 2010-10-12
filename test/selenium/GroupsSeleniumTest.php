<?php
require_once 'lib/config.php';
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class GroupsSeleniumTest extends PHPUnit_Extensions_SeleniumTestCase {
	protected function setUp(){
		SCHelper::setUp($this, "fast");
	}
	public function testAddandDeleteGroup(){		
		SCHelper::login($this);
		
		$coursename = SCHelper::uid("Test Course");
		$groupname = SCHelper::uid("Test Group");
		
		//open courses page & add course
		$this->open("/courses");
		$this->click("showadd");
		$this->type("id=coursename", $coursename);
		$this->click("id=coursesubmit");
		
        SCHelper::waitForAjax($this); 

		//go to course page so that we can add groups
		$this->click("xpath=//a[text()='$coursename']");
		$this->waitForPageToLoad();
		$this->assertTextPresent($coursename);
		    
		//add a group
		$this->click("addgroup");
		$this->type("id=groupname", $groupname);
		$this->click("id=addcoursebutton"); 
		   
		//check to see if group was added      
		$this->waitForPageToLoad();
		$this->assertTextPresent($groupname);    
		
		//delete group
		$this->click("xpath=//div[text()='$groupname']/../../../img[@name='delete']");
		$this->getConfirmation();
		SCHelper::waitForAjax($this);
		
		//refresh to force that it isnt on the page
		$this->refresh();
		$this->waitForPageToLoad();
		$this->assertTextNotPresent($groupname);
	}
}
?>