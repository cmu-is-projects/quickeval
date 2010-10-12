<?php
require_once 'lib/config.php';
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class MySettingsSeleniumTest extends PHPUnit_Extensions_SeleniumTestCase {
	protected function setUp(){
		SCHelper::setUp($this);
	}
	public function testNotLoggedInDenied(){
		SCHelper::logout($this);
		$this->open("/mysettings");
		$this->assertTextPresent("You must be logged in to access the my settings page");
	}
	public function testChangeSettings(){
		SCHelper::login($this);
		$this->open("/mysettings");
		$this->waitForPageToLoad();
		
		$this->type("id=fname", "Test");
		$this->type("id=lname", "Runner");
		$this->click("saveForm");
		SCHelper::waitForAjax($this);  
		   	
		$this->assertTextPresent("Test");	
		$this->assertTextPresent("Runner");
		
		$this->type("id=oldpass", "abc123");
		$this->type("id=newpw1", "abc1234");
		$this->type("id=newpw2", "abc1234");
		$this->click("saveForm");  
		SCHelper::waitForAjax($this);
		
		SCHelper::logout($this);
		SCHelper::login($this, "test@mail.com", "abc1234");

		$this->open("/mysettings");
		$this->waitForPageToLoad();
	
		$this->type("id=oldpass", "abc1234");
		$this->type("id=newpw1", "abc123");
		$this->type("id=newpw2", "abc123");
		$this->click("saveForm"); 
		SCHelper::waitForAjax($this);
		
		SCHelper::logout($this);
		SCHelper::login($this, "test@mail.com", "abc123");

		$this->waitForPageToLoad();
	}
	
}
?>