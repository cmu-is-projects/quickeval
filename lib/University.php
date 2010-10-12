<?php
/** 
* University Class
* @author sbenders
* @package QuickEval
*/
require_once("config.php");

class University extends CustomClass {	
	protected function allowed_parameters(){
		return array('id', 'name');
	}
	protected function required_parameters(){
		return array('name');
	}
	protected function default_parameters(){
		return array();
	}
	public function tablename(){ return "Universities"; }
	
	public function validate_parameter($name, $value){
		$errors = array();
		switch ($name){
			case "id":
				if (!Validation::validate_id($value))
					$errors[] = "ID must be greater than 0";
				break;
			case "name":
				if (!Validation::validate_presence_of($value))
					$errors[] = "Please enter the University Name";
				break;
				
			default:
				return $errors;
		}
		return $errors;
	}
}
?>