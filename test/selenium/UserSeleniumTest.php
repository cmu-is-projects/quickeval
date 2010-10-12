<?php
require_once "lib/config.php";
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class UserSeleniumTest extends PHPUnit_Extensions_SeleniumTestCase {
	protected function setUp(){
		$this->setBrowser('*'.SELENIUM_BROWSER);
		$this->setBrowserUrl(WEB_ROOT);
	}
	private function log_in($email = "test@mail.com", $pass = "abc123"){
		$this->open("/logout");
		$this->open("/");
		$this->waitForPageToLoad();
		$this->type("user_name", $email);
		$this->type("password", $pass);
		$this->submit("login_form");
		$this->waitForPageToLoad();
	}
	public function testLogin(){
		$this->log_in();
		$this->assertTextPresent("Login Successful");
	}
	public function testLogout(){
		$this->log_in();
		$this->open("/logout");
		$this->waitForPageToLoad();
		$this->assertTextPresent("You have been successfully logged out");
	}
	
}
?>