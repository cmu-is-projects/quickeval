<?php
/** 
* Response Class
* @author Lauren Taglieri
* @package QuickEval
*/
require_once("config.php");

class Response extends CustomClass {
	protected function allowed_parameters(){
		return array('id', 'user_id', 'survey_instance_id', 'question_id', 'user_for', 'value', 'timestamp', 'active');
	}
	protected function required_parameters(){
		return array('user_id', 'survey_instance_id', 'question_id', 'user_for', 'value', 'active');
	}
	protected function default_parameters(){
		return array('active' => 1);
	}
	public function tablename(){ return "Responses";	}

	public function validate_parameter($name, $value){
		$errors = array();
		switch ($name){
			case "id":
				if (!Validation::validate_id($value))
					$errors[] = "ID must be greater than 0";
				break;
			case "user_id":
				if (!Validation::validate_fk($value))
					$errors[] = "User Id must be greater than 0, or -1";
				break;
			case "survey_instance_id":
				if (!Validation::validate_fk($value))
					$errors[] = "Survey Instance Id must be greater than 0";
				break;
			case "question_id":
				if (!Validation::validate_fk($value))
					$errors[] = "Question Id must be greater than 0";
				break;
			case "user_for":
				if (!Validation::validate_fk($value))
					$errors[] = "User_for Id must be greater than 0";
				break;
			case "value":
				if (!Validation::validate_presence_of($value))
					$errors[] = "Value must not be blank";
				break;
			case "active":
				if (!Validation::validate_numeric_range($value, 0, 1))
					$errors[] = "Active can only be 1 or 0";
				break;
			default:
				return $errors;
		}
		return $errors;
	}
	
}

?>
