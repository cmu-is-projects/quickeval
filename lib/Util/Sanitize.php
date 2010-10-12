<?php
/**
 * Sanitize
 *
 * @package QuickEval
 * @author Ari Rubinstein
 **/
class Sanitize {
	/**
	 * Trims string, no HTML allowed, plain text
	 *
	 * @param string $input the input to be sanitized 
	 * @return string trimmed string with no HTML and plain text
	 * @author Ari Rubinstein
	 **/
	public static function s_plain($input){
		return htmlentities(trim($input), ENT_NOQUOTES);
	}
	
	/**
	 * trims a string, no HTML allowed
	 *
	 * @param string $input the input to be sanitized 
	 * @return string trimmed string with no HTML
	 * @author Ari Rubinstein
	 **/
	public static function s_nohtml($input){
		return htmlentities(trim($input), ENT_QUOTES);
	}
	
	/**
	 * Trims string
	 *
	 * @param string $input the input to be sanitized 
	 * @return string trimmed string 
	 * @author Ari Rubinstein
	 **/
	public static function s_trim($input){
		return trim($input);
	}

	/**
	 * Trims string, upper casing words
	 *
	 * @param string $input the input to be sanitized 
	 * @return string trimmed string with uppercased words
	 * @author Ari Rubinstein
	 **/	
	public static function s_upper_word($input){
		return ucwords(strtolower(trim($input)));
	}
	
	/**
	 * Trims string, upper casing first word
	 *
	 * @param string $input the input to be sanitized 
	 * @return string trimmed string with the first word uppercased
	 * @author Ari Rubinstein
	 **/	
	public static function s_uc_first_word($input){
		return ucfirst(strtolower(trim($input)));
	}
	
	/**
	 * Trims string, lowercasing string
	 *
	 * @param string $input the input to be sanitized 
	 * @return string trimmed string in lowercase
	 * @author Ari Rubinstein
	 **/	
	public static function s_lower($input){
		return strtolower(trim($input));
	}
	
	/**
	 * Trims string, converting to encoded URL
	 *
	 * @param string $input the input to be sanitized 
	 * @return string trimmed string as encoded URL
	 * @author Ari Rubinstein
	 **/	
	public static function s_urle($input){
		return urlencode(trim($input));
	}
		
} // END class 


?>
