<?php
/**
* This file contains the Text Utility Class
* 
* @package QuickEval
*/
class Text {
	const NUMBERS_LETTERS_SPACE = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	
	public static function bool($val, $true = "Yes", $false = "No"){
		return Text::boolean_to_english($val, $true, $false);
	}
	
	
	/**
	 * returns a date formatted like May 6, 2009
	 *
	 * @return string a date formatted like May 6, 2009
	 * @param string $date the date to process (must work with strtotime)
	 * @author Ari Rubinstein
	 **/
	public static function readable_date($date){
		return date("M j, Y", strtotime($date));
	}
	
	/**
	 * Returns the letter "s" if the input number is not equal to 1.
	 * Returns the empty string "" otherwise
	 * @param int $num the quantity of things in question
	 * @return string
	 */
	public static function plural($num){
		if ($num == 1){
			return "";
		} else {
			return "s";
		}
	}

	/**
	 * Returns the true_text if the boolean is true, else returns the false_text
	 * @param boolean $input the boolean in question
	 * @param string $true_text the text to be returned if the value is true
	 * @param string $false_text the text to be returned if the value is false
	 * @return string
	 */
	function boolean_to_english($input, $true_text = "Active", $false_text = "Not Active"){
		if ($input)
			return $true_text;
		return $false_text;
	}
	
	/**
	* Returns a random string with a certain character set
	* @param integer $length the length of the string
	* @param string $charset the string of characters to choose from
	* @return string the random string
	*/
	function random_string($length, $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"){
		$thestring = "";
		while (strlen($thestring) < $length) {
			$thestring .= substr($charset, rand(0, strlen($charset)-1), 1);
		}
		return $thestring;
	}
	
	/**
	* Takes in a URL and returns a hash of the querystring arguments
	* @param string $url the url to parse
	* @return array the assoc array of the querystring values
	*/
	function querystring_to_hash($url){
		preg_match_all('/[?|&]([^=%]+)=([^&]+)/x', $url, $result, PREG_SET_ORDER);
		$vars = array();
		for ($matchi = 0; $matchi < count($result); $matchi++) {
			$vars[$result[$matchi][1]] = $result[$matchi][2];
		}
		return $vars;
	}
	
	static function cleanStringForCSV($string){
		return "\"". str_replace("\"","\\\"",$string) . "\"";
	}
	
	/**
	* Returns a text only and numbers only string.  you can customize the filter
	* @param string $inString the input string to clean
	* @return string The stripped string
	*/
	static function onlyCharsAndNumbersAndSpace($inString){
		return ereg_replace("[^A-Za-z0-9 _]", "", $inString);
	}
	
	static function spacesToUnderscores($input){
		return str_replace(" ", "_", $input);
	}

	/**
	 * returns an array of users depending on the mode of sorting
	 *
	 * @param string $list the list of users separated by lines
	 * @param integer $mode the mode to separate out the sorting.  1 = csv, 2 = andrew
	 * @return array of first name, last name, and email
	 * @author Ari Rubinstein
	 **/
	function list_to_users($list, $mode=1){
		if ($list == "")
			return null;
		$lines = explode("\n", $list);
		if ($lines == null)
			return null;
		$names = array();
		foreach ($lines as $l){
			$data = explode(",", $l);
			switch ($mode){
				case 1:
					//csv: email,lastname,firstname
					$names[] = array(
						"email" => strtolower($data[0]),
						"fname" => $data[2],
						"lname" => $data[1]
						);
					break;
				case 2:
					//andrew: year,andrew,lastname,firstname
					$names[] = array(
						"email" => strtolower($data[1]."@andrew.cmu.edu"),
						"fname" => $data[3],
						"lname" => $data[2]
						);
					break;
				default:
					return null;
					break;
			}
		}
		return $names;
		
	}
}
?>
