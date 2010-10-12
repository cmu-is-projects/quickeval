<?php
/**
* This is the testing class for the User class
* @package QuickEvalTest
* @author Ari Rubinstein
*/
require_once "lib/config.php";

class UserTest extends PHPUnit_Framework_TestCase {
	public function testParameterValidation(){
		$u = new User();
		$this->assertFalse($u->validate(), "User is invalid");
		$u->set_password("asd");
		$u->email = "bademail";
		$this->assertFalse($u->validate(), "User still invalid - email is bad");
		$u->email = "thisisavalidemailaddressquickevalrules@andrew.cmu.edu";
		$u->validate();
		$this->assertTrue($u->validate(), "Make sure that user is valid");
		$this->assertTrue($u->save(), "Couldn't save User");
		$result = $u->id;
		$otherUser = new User($result);
		$this->assertEquals($u->email, $otherUser->email, "Email from retrieved object is not the same");
		
		$this->assertTrue($otherUser->force_delete(), "Couldn't delete object");
		try {
			$deletedUser = new User($result);
			$this->assertNull($deletedUser);
		} catch(Exception $e){
			
		}
	}
	
	public function testFindingInvalidUser(){
		try {
			$deletedUser = new User(-1);
			$this->assertNull($deletedUser);
		} catch(Exception $e){
			
		}
	}
	
	public function testUserLogin(){
		$u = new User();
		$u->set_password("my_password");
		$this->assertTrue($u->check_password("my_password"));
		$this->assertFalse($u->check_password("my_password1a"));
	}
}
?>