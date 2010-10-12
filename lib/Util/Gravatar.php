<?php
/**
* This file contains the Gravatar Class
* 
* @package QuickEval
*/
class Gravatar {
	/**
	 * Returns a Gravatar html for the email input
	 *
	 * @param string $email the email of the user, @param int $size the size of the gravatar, @param string $default the png gravatar
	 * @return string the url of the gravatar
	 * @author Ari Rubinstein
	 **/
	public static function getGravatarImageLocation($email, $size=80, $default="http://www.quickeval.org/images/default.png"){
		return 'http://www.gravatar.com/avatar.php?gravatar_id='.md5(strtolower($email)).'&default='.urlencode($default).'&size='.$size;
	}
}

?>