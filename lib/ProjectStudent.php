<?php
/** 
* ProjectStudent Class
* @author Sbenders
* @package QuickEval
*/
require_once("config.php");

class ProjectStudent extends CustomClass {	
	protected function allowed_parameters(){
		return array('id', 'projectteam_id', 'user_id');
	}
	protected function required_parameters(){
		return array('projectteam_id', 'user_id');
	}
	protected function default_parameters(){
		return array();
	}
	public function tablename(){ return "ProjectStudents"; }
	
	public function validate_parameter($name, $value){
		$errors = array();
		switch ($name){
			case "id":
				if (!Validation::validate_id($value))
					$errors[] = "ID must be greater than 0";
				break;
			case "projectteam_id":
				if (!Validation::validate_fk($value))
					$errors[] = "Project Team ID must be greater than 0, or -1";
				break;
			case "user_id":
				if (!Validation::validate_fk($value)) 
					$errors[] = "User ID must be greater than 0, or -1";
				break;

			default:
				return $errors;
		}
		return $errors;
	}
}
?>