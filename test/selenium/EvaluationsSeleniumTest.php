<?php
require_once 'lib/config.php';
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class EvaluationsSeleniumTest extends PHPUnit_Extensions_SeleniumTestCase {
	protected function setUp(){
		SCHelper::setUp($this, "fast");
	}

	public function testNotLoggedInDenied(){
		SCHelper::logout($this);
		$this->open("/evaluations");
		$this->assertTextPresent("You must be logged in to access the Evaluations page");
	}
	
	public function testCreateAndDeleteEvaluation(){
		SCHelper::login($this);
		
		$surveyname = SCHelper::uid("Test Survey");
		$surveydescription = SCHelper::uid("Test Survey Description");
		
		//open evaluations page
		$this->open("/evaluations");
		
		//click on the show add button
		$this->click("showadd");
		
		//submits the new survey
		$this->type("id=surveyname", $surveyname);
		$this->type("id=surveydescription", $surveydescription);
		$this->click("id=surveysubmit");
		
		SCHelper::waitForAjax($this);
		
		//check to see if the ajax add worked
		$this->assertTextPresent($surveyname);
		$this->assertTextPresent($surveydescription);      

		//delete survey
		$this->click("xpath=//a[text()='$surveyname']/../img[@name='delete']");
		$this->getConfirmation();
		SCHelper::waitForAjax($this);

		//refresh to force that it isnt on the page
		$this->refresh();
		$this->waitForPageToLoad();
		$this->assertTextNotPresent($surveyname);
		$this->assertTextNotPresent($surveydescription);
	}        
	
}	
?>