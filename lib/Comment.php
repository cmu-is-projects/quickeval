<?php
/** 
* Comment Class
* @author Lauren Taglieri
* @package QuickEval
*/
require_once("config.php");

class Comment extends CustomClass {
	protected function allowed_parameters(){
		return array('id', 'response_id', 'detail');
	}
	protected function required_parameters(){
		return array('response_id', 'detail');
	}
	protected function default_parameters(){
		return array();
	}
	public function tablename(){ return "Comments";	}
	
	public function validate_parameter($name, $value){
		$errors = array();
		switch ($name){
			case "id":
				if (!Validation::validate_id($value))
					$errors[] = "ID must be greater than 0";
				break;
			case "response_id":
				if (!Validation::validate_fk($value))
					$errors[] = "Response Id must be greater than 0";
				break;
			case "detail":
				if (!Validation::validate_presence_of($value))
					$errors[] = "Comment must not be blank";
				break;
			default:
				return $errors;
		}
		return $errors;
	}
	
}

?>
