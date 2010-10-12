<?php
require_once("config.php");
class SCHelper {
	const TEST_TEACHER_USERNAME = "test@mail.com";
	const TEST_TEACHER_PASSWORD = "abc123";
	
	public static function uid($prefix=""){
		return "${prefix}_".Text::random_string(10);
	}          
	
	public static function getLinkedId($t, $selector, $parameter){
		$a = Text::querystring_to_hash($t->getAttribute($selector."/@href"));
		return $a[$parameter];
	}                          
	
	public static function setUp($t, $speed="normal"){
		$t->setBrowser('*'.SELENIUM_BROWSER);
		$t->setBrowserUrl(WEB_ROOT);
		switch ($speed){
			case "fast":
				$t->setSpeed(250);
				break;
			case "slow":
				$t->setSpeed(1000);
				break;
			default:
				$t->setSpeed(500);
				break;
		}
	}
	
	public static function waitForAjax($t, $secondsToWait = 15){
		$t->waitForCondition("(selenium.browserbot.getCurrentWindow().document.getElementById('ajaxloadingindicator').style.display == 'none');", 1750 * $secondsToWait);
		
	}
	
	public static function login($t, $email = self::TEST_TEACHER_USERNAME, $pass = self::TEST_TEACHER_PASSWORD){
		SCHelper::logout($t);
		$t->open("/");
		$t->waitForPageToLoad();
		$t->type("user_name", $email);
		$t->type("password", $pass);
		$t->submit("login_form");
		$t->waitForPageToLoad();
	}
	
	public static function logout($t){
		$t->open("/logout");
		
	}
}
?>