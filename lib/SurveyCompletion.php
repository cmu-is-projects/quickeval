<?php
/** 
* Survey Completion Class
* @author arubinst
* @package QuickEval
*/
require_once("config.php");

class SurveyCompletion extends CustomClass {	
	protected function allowed_parameters(){
		return array('id', 'survey_instance', 'user_id', 'user_for_id');
	}
	protected function required_parameters(){
		return array('survey_instance', 'user_id', 'user_for_id');
	}
	protected function default_parameters(){
		return array();
	}
	public function tablename(){ return "SurveyCompletions"; }
	
	public function validate_parameter($name, $value){
		$errors = array();
		switch ($name){
			case "id":
				if (!Validation::validate_id($value))
					$errors[] = "ID must be greater than 0";
				break;
			case "survey_instance":
				if (!Validation::validate_id($value))
					$errors[] = "Survey Instance must be a valid ID";
				break;
			case "user_id":
				if (!Validation::validate_id($value))
					$errors[] = "User ID must be a valid ID";
				break;
			case "user_for_id":
				if (!Validation::validate_id($value))
					$errors[] = "User For ID must be a valid ID";
				break;

			default:
				return $errors;
		}
		return $errors;
	}
	
	
}
?>