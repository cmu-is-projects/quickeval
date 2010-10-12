<?php
require_once 'lib/config.php';
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class SendEvaluationsSeleniumTest extends PHPUnit_Extensions_SeleniumTestCase {
	protected function setUp(){
		SCHelper::setUp($this, "fast");
	}
	public function testSendEvaluation(){		
		SCHelper::login($this);
		
		$coursename = SCHelper::uid("Test Course");
		$eval = SCHelper::uid("Test Eval"); 
		$evaldescript = SCHelper::uid("Test Eval Description");
		$shortanswer = SCHelper::uid("Test Short Answer");    
		
		//open evaluation page & create eval
		$this->open("/evaluations");
		$this->click("showadd");
		$this->type("id=surveyname", $eval);
		$this->type("id=surveydescription", $evaldescript);
		$this->click("id=surveysubmit");
		
		SCHelper::waitForAjax($this);  
		
		//go to evaluation page so that we can add questions
		$this->click("xpath=//a[text()='$eval']");
		$this->waitForPageToLoad();
		$this->assertTextPresent($eval);
	
		//add a SHORT ANSWER question
		$this->click("showadd");
		$this->select("questiontype", "Short Answer");
		$this->type("questionname", $shortanswer);
		$this->click("id=addquestionbutton");
		
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
		    
	    //add two students
	    $this->click("id=showadd");
		$this->type("id=studentlist", "needhelpcallari+hello@gmail.com,Test,Cool");
		$this->click("id=surveysubmit");
		$this->refresh(); 
		$this->waitForPageToLoad();
		$this->click("showadd");
		$this->type("name=names", "ltaglieri+yo@gmail.com,Test,Student2");
		$this->click("id=surveysubmit");  
		
		//check to see if students were added      
		$this->waitForPageToLoad();
		$this->assertTextPresent("needhelpcallari+hello@gmail.com");
		$this->assertTextPresent("ltaglieri+yo@gmail.com");  
		
		//send evaluation
		$this->click("id=sendevaluation");
		$this->select("xpath=(//select[@name='courseid'])[last()]", $coursename);
		$this->select("xpath=(//select[@name='surveyid'])[last()]", $eval);
		$this->type("xpath=(//input[@name='due_m'])[last()]", "05");
		$this->type("xpath=(//input[@name='due_d'])[last()]", "01");
		$this->type("xpath=(//input[@name='due_y'])[last()]", "2009");  
		$this->submit("xpath=(//form[@name='sendevaluation'])[last()]");
	}    
}
?>