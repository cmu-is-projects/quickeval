<?php
/**
 * Validation Class
 * Has miscellaneous methods for validation
 *
 * @package QuickEval
 * @author Ari Rubinstein
 **/

class Validation {

	/**
	 * Validates an IP address
	 *
	 * @return boolean true if input is a valid IP address
	 * @author Ari Rubinstein
	 **/
	public static function validate_ip($value){
		return preg_match( "/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/", $value);
	}
	
	/**
	 * Makes sure that the value submitted is an integer
	 *
	 * @return boolean true if the input is an integer
	 * @author Ari Rubinstein
	 **/
	public static function valid_positive_integer($value){
		return preg_match('@^[0-9]+$@',$value) === 1;
	}
	
	/**
	 * Makes sure a value is within the numeric range provided
	 *
	 * @return boolean true if number is within numeric range
	 * @author Ari Rubinstein
	 **/
	public static function validate_numeric_range($value, $start, $end){
		return (is_numeric($value) && $value >= $start && $value <= $end);
	}
	
	/**
	 * Validates a foreign key (ie, valid_id or -1)
	 *
	 * @return boolean true if input is a valid foreign key
	 * @author Ari Rubinstein
	 **/
	public static function validate_fk($value){
		return (Validation::validate_id($value) || $value == -1);
	}
	
	/**
	 * Makes sure a value is a valid ID
	 */
	public static function validate_id($value){
		return (int)$value > 0;
	}
	
	/**
	 * Verifies that a string is at least x characters long
	 *
	 * @return boolean true if string is at least that many characters long
	 * @author Ari Rubinstein
	 **/
	public static function validate_string_minimum_length($value, $length){
		return strlen($value) >= $length;
	}
	/**
	 * Makes sure a variable is set and isnt "" or null
	 */
	public static function validate_presence_of($value){
		if (!isset($value)){
			return false;
		} else if ($value == ""){
			return false;
		} else if ($value == null){
			return false;
		} else {
			return true;
		}
		
	}
	/**
	 * Verifies an email address.  Returns true if valid
	 * @param string $email the email address to be verified
	 * @return boolean
	 */
	public static function validate_email($email) {
		// First, we check that there's one @ symbol, 
		// and that the lengths are right.
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
			// Email invalid because wrong number of characters 
			// in one section or wrong number of @ symbols.
			return false;
		}
		// Split it into sections to make life easier
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			if(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
			?'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
			$local_array[$i])) {
				return false;
			}
		}
		// Check if domain is IP. If not, 
		// it should be valid domain name
		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
				return false; // Not enough parts to domain
			}
			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
					?([A-Za-z0-9]+))$",
				$domain_array[$i])) {
					return false;
				}
			}
		}
		return true;
	}
}

?>