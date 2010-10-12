<?php
/**
* This class contains tests for the Gravatar Class
* @package QuickEvalTest
* @author Ari Rubinstein
*/
require_once "config.php";
require_once "Util/Gravatar.php";
class GravatarTest extends PHPUnit_Framework_TestCase {
	/**
	* Gravatar Image Grabbing successful test
	* Fetches the image from gravatar site and ensures that it is the right size
	*/
	public function testGravatarCanGrabValidImage(){
		$emailToTest = 'needhelpcallari@gmail.com';
		$sizeWeWant = 80;
		$gravatarurl = Gravatar::getGravatarImageLocation($emailToTest, $sizeWeWant);
			$this->assertEquals("http://www.gravatar.com/avatar.php?gravatar_id=af2a988d2a7d7b37b84c89cb80dbc102&default=http%3A%2F%2Fdev.quickeval.org%2Fimages%2Fdefault.png&size=80", $gravatarurl);
	}
	
}
?>