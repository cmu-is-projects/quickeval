<?php
/** 
* QuestionData Class
* @author Ari Rubinstein
* @package QuickEval
*/

require_once("config.php");

class QuestionData {

	public $name = "";
	public $choices = array();    
	
	/**
	 * Assigns the name and choices that are passed into the function to the question
	 *    
	 * @param string $name for question name, array $choices for question choices
	 * @author Ari Rubinstein
	 **/
	public function QuestionData($name = "", $choices = array()){
		$this->name = $name;
		$this->choices = $choices;
	}       
	
	/**
	 * Serializes the variable - converts it to a string
	 *    
	 * @return string of the serialized value
	 * @author Ari Rubinstein
	 **/
	public function __toString(){
		return serialize($this);
	}

}

?>