<?php
require_once 'lib/config.php';
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class ViewResultsProfSeleniumTest extends PHPUnit_Extensions_SeleniumTestCase {
	protected function setUp(){
		SCHelper::setUp($this, "fast");
	}
	
	public function testViewResults(){	
		//login as professor	
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
	    $this->click("showadd");
		$this->type("name=names", "needhelpcallari+hello@gmail.com,Test,Student");
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
		
		//force users to have default passwords and activated accounts
		$u = new User();
		$ari = $u->findOne(array("email" => "needhelpcallari+hello@gmail.com"));
		$this->assertNotNull($ari);
		$ari->set_password("abc1234");
		$ari->level = User::U_STUDENT;
		if (!$ari->save()){
			$this->assertTrue(false);
		}
			
		$lauren = $u->findOne(array("email" => "ltaglieri+yo@gmail.com"));
		$this->assertNotNull($lauren);
		$lauren->set_password("abc1234");
		$lauren->level = User::U_STUDENT;
		if (!$lauren->save()){
			$this->assertTrue(false);
		}

		//send evaluation
		$this->click("id=sendevaluation");
		$this->select("xpath=(//select[@name='courseid'])[last()]", $coursename);
		$this->select("xpath=(//select[@name='surveyid'])[last()]", $eval);
		$this->type("xpath=(//input[@name='due_m'])[last()]", "05");
		$this->type("xpath=(//input[@name='due_d'])[last()]", "01");
		$this->type("xpath=(//input[@name='due_y'])[last()]", "2009");  
		$this->submit("xpath=(//form[@name='sendevaluation'])[last()]");

		$this->waitForPageToLoad();
		
		//log out & log in as student
		$this->open("/logout");
		$this->open("/");
		$this->waitForPageToLoad();
		$this->type("user_name", "needhelpcallari+hello@gmail.com");
		$this->type("password", "abc1234");
		$this->submit("login_form");
		$this->waitForPageToLoad(); 
		
		//complete evaluation
		$this->open("/evaluations");
		$this->click("xpath=//div/ol/li/div/ul/li/a/img[@alt='open-incomplete']"); 
		$this->waitForPageToLoad();
		$this->assertTextPresent("Student2");  
		$this->type("xpath=//div/textarea", "good");  
		$this->click("id=submitsurveyresponse");       
		$this->waitForPageToLoad();
		$this->assertTextPresent("Survey successfully completed for Student2 Test");

		//log in as professor
		SCHelper::login($this);
		
		//view results
		$this->open("/courses");
		$this->waitForPageToLoad();
		SCHelper::waitForAjax($this);
		$this->click("xpath=//a[text()='$coursename']");
		$this->waitForPageToLoad();
		$this->assertTextPresent($coursename);	
		$this->click("id=viewresults");
		$this->waitForPageToLoad();
		$this->assertTextPresent("Results");
		$this->assertTextPresent($coursename . ": " . "Default Group"); 
		$this->click("xpath=//div/ol/li/div/ul/li/a/img[@src='ui/images/viewresults.png']");
		$this->assertTextPresent($coursename);
	}
}
?>