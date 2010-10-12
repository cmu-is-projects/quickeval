<?php
/** 
* ProjectTeam Class
* @author benders
* @package QuickEval
*/
require_once("config.php");

class ProjectTeam extends CustomClass {	
	protected function allowed_parameters(){
		return array('id', 'owner_id', 'course_id', 'name');
	}
	protected function required_parameters(){
		return array('course_id', 'owner_id', 'name');
	}
	protected function default_parameters(){
		return array('owner_id' => User::current_user_id());
	}
	public function tablename(){ return "ProjectTeams"; }
	
	public function validate_parameter($name, $value){
		$errors = array();
		switch ($name){
			case "id":
				if (!Validation::validate_id($value))
					$errors[] = "ID must be greater than 0";
				break;
			case "user_id":
				if (!Validation::validate_fk($value))
					$errors[] = "User ID must be greater than 0, or -1";
				break;
			case "owner_id":
				if (!Validation::validate_fk($value)) 
					$errors[] = "Owner ID must be greater than 0";
				break;
			case "name":
				if (!Validation::validate_presence_of($value))
					$errors[] = "Please enter the Project Team name";
				break;
				
			default:
				return $errors;
		}
		return $errors;
	}
	
	/**
	 * Returns the number of students within a course
	 *
	 * @return int the number of students within a course
	 * @author Ari Rubinstein
	 **/
	public function num_project_students(){
		if (!$this->solid()){
			//no groups, because this class doesn't even exist in the database
			return 0;
		} else {
			$db = DBClass::start();
			$sql = "SELECT * FROM ProjectStudents WHERE ".$db->arg("projectteam_id", $this->id);
			$result = $db->select_count($sql);
			if (!$result){
				return 0;
			} else {
				return 1;
			}
		}
	}
}
?>