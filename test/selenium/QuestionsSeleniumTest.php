<?php
require_once "lib/config.php";
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class QuestionsSeleniumTest extends PHPUnit_Extensions_SeleniumTestCase {
	protected function setUp(){
		SCHelper::setUp($this, "fast");
	}
	public function testAddQuestions(){
		SCHelper::login($this);  
	
		$surveyname = SCHelper::uid("Test Survey");   
		$surveydescription = SCHelper::uid("Test Survey Description");     

		$shortanswer = SCHelper::uid("Test Short Answer");
		$numeric = SCHelper::uid("Test Numeric Input");     
		$multiple = SCHelper::uid("Test Multiple Choice"); 
		$rating = SCHelper::uid("Test Rating");
		$checkbox = SCHelper::uid("Text Checkbox");        
		$choice1 = SCHelper::uid("Test Choice 1"); 
	    $choice2 = SCHelper::uid("Test Choice 2");
	            
		//open evaluations page & add evaluation
		$this->open("/evaluations");        
		$this->click("showadd");   
		$this->type("id=surveyname", $surveyname);
		$this->type("id=surveydescription", $surveydescription);
		$this->click("surveysubmit");
	
		SCHelper::waitForAjax($this); 
	
		//go to evaluation page so that we can add questions
		$this->click("xpath=//a[text()='$surveyname']");
		$this->waitForPageToLoad();
		$this->assertTextPresent($surveyname);
	
		//add a SHORT ANSWER question
		$this->click("showadd");
		$this->select("questiontype", "Short Answer");
		$this->type("questionname", $shortanswer);
		$this->click("id=addquestionbutton");   
	
		//check that question was added
		$this->waitForPageToLoad();   
		$this->assertTextPresent($shortanswer); 
	
		//delete short answer question
		$this->click("xpath=//label[text()='$shortanswer']/../../../../td/img[@alt='delete']");
		$this->getConfirmation();
		SCHelper::waitForAjax($this);
	
		//refresh to force that it isnt on the page
		$this->refresh();
		$this->waitForPageToLoad();
		$this->assertTextNotPresent($shortanswer);
	
		//add a NUMERIC INPUT question
		$this->click("showadd");
		$this->select("questiontype", "Numeric Input");
		$this->type("questionname", $numeric);  
		$this->click("id=addquestionbutton");
	
		//check that question was added 
		$this->waitForPageToLoad();    
		$this->assertTextPresent($numeric); 
		
		//delete numeric input question 
		$this->click("xpath=//label[text()='$numeric']/../../../../td/img[@alt='delete']"); 
		$this->getConfirmation();
		SCHelper::waitForAjax($this);
		
		//refresh to force that it isn't on the page
		$this->refresh();
		$this->waitForPageToLoad();
		$this->assertTextNotPresent($numeric);
		
		//add a MULTIPLE CHOICE question
		$this->click("showadd");
		$this->select("questiontype", "Multiple Choice");
		$this->type("id=questionname", $multiple);         
		$this->type("xpath=id('moredetails')/input[last()-1]", $choice1);  
		$this->click("addmore");
		$this->type("xpath=id('moredetails')/input[last()-1]", $choice2);
		$this->click("id=addquestionbutton"); 
		
		//check that question was added 
	   	$this->waitForPageToLoad(); 
		$this->assertTextPresent($multiple);
		$this->assertTextPresent($choice1);  
		$this->assertTextPresent($choice2); 
		    
		//delete multiple choice question
		$this->click("xpath=//label[text()='$multiple']/../../../../td/img[@alt='delete']");
		$this->getConfirmation();
		SCHelper::waitForAjax($this);
		
		//refresh to force that it isn't on the page
		$this->refresh();
		$this->waitForPageToLoad();
		$this->assertTextNotPresent($multiple);
		
	    //add a RATING question
		$this->click("showadd");
		$this->select("questiontype", "Rating");
		$this->type("id=questionname", $rating);       
		$this->click("addmore");  
		$this->type("xpath=id('moredetails')/input[last()]", $choice1);  
		$this->click("addmore");
		$this->type("xpath=id('moredetails')/input[last()-1]", $choice2);
		$this->click("id=addquestionbutton"); 
		
		//check that question was added 
	   	$this->waitForPageToLoad(); 
		$this->assertTextPresent($rating);
		$this->assertTextPresent($choice1);  
		$this->assertTextPresent($choice2); 
		    
		//delete rating question
		$this->click("xpath=//label[text()='$rating']/../../../../../../../td/img[@alt='delete']");
		$this->getConfirmation();
		SCHelper::waitForAjax($this);
		
		//refresh to force that it isn't on the page
		$this->refresh();
		$this->waitForPageToLoad();
		$this->assertTextNotPresent($rating);    
		
		//add a CHECKBOX question
		$this->click("showadd");
		$this->select("questiontype", "Checkboxes");
		$this->type("id=questionname", $checkbox);       
		$this->click("addmore");  
		$this->type("xpath=id('moredetails')/input[last()-1]", $choice1);  
		$this->click("addmore");
		$this->type("xpath=id('moredetails')/input[last()-1]", $choice2);
		$this->click("id=addquestionbutton"); 
		
		//check that question was added 
	   	$this->waitForPageToLoad(); 
		$this->assertTextPresent($checkbox);
		$this->assertTextPresent($choice1);  
		$this->assertTextPresent($choice2); 
		    
		//delete rating question
		$this->click("xpath=//label[text()='$checkbox']/../../../../td/img[@alt='delete']");
		$this->getConfirmation();
		SCHelper::waitForAjax($this);
		
		//refresh to force that it isn't on the page
		$this->refresh();
		$this->waitForPageToLoad();
		$this->assertTextNotPresent($checkbox);
	}  
}
?>
