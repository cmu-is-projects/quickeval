<?php
/** 
* Course Class
* @author Ari Rubinstein
* @package QuickEval
*/
require_once("config.php");

class Course extends CustomClass {
	protected function allowed_parameters(){
		return array('id', 'owner_id', 'name', 'active');
	}
	protected function required_parameters(){
		return array('name', 'owner_id');
	}
	protected function default_parameters(){
		return array('owner_id' => User::current_user_id(), 'active' => 1);
	}
	public function tablename(){ return "Courses";	}
	
	public function validate_parameter($name, $value){
		$errors = array();
		switch ($name){
			case "id":
				if (!Validation::validate_id($value))
					$errors[] = "ID must be greater than 0";
				break;
			case "owner_id":
				if (!Validation::validate_fk($value))
					$errors[] = "Owner Id must be greater than 0";
				break;
			case "name":
				if (!Validation::validate_presence_of($value))
					$errors[] = "Course name must not be blank";
				if (!Validation::validate_string_minimum_length($value, 2))
					$errors[] = "Course name must be at least 2 characters long";
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
	
	/**
	 * Returns the number of students within a course
	 *
	 * @return int the number of students within a course
	 * @author Ari Rubinstein
	 **/
	public function num_students(){
		if (!$this->id){
			return 0;
		}	
		$ptf = new ProjectTeam();
		$pts = $ptf->find(array("course_id" => $this->id));
		$psf = new ProjectStudent();
		$studentcount = 0;
		if ($pts){
			foreach ($pts as $projectteam){
				$tempps = $psf->find(array("projectteam_id" => $projectteam->id));
				if ($tempps){
					$studentcount += count($tempps);
				}
			}
		}
		return $studentcount;
	}   
	
	/**
	 * Returns whether a student is in a course or not
	 *    
	 * @param int $student_id id for the student
	 * @return boolean true if the student is in this course
	 * @author Ari Rubinstein
	 **/
	public function student_in_course($student_id){
		if (!$this->id){
			return 0;
		}	
		$ptf = new ProjectTeam();
		$pts = $ptf->find(array("course_id" => $this->id));
		$psf = new ProjectStudent();
		if ($pts){
			foreach ($pts as $projectteam){
				$tempps = $psf->find(array("projectteam_id" => $projectteam->id));
				if ($tempps){
					foreach ($tempps as $projectstudent){
						if ($projectstudent->user_id == $student_id){
							return true;
						}
					}
				}
			}
		}
		return false;
	}
	
	/**
	 * Returns the number of groups within a course
	 * 
	 * @return int the number of groups within a course
	 * @author Lauren Taglieri
	 **/
	public function num_teams(){
		if (!$this->solid()){
			//no groups, because this class doesn't even exist in the database
			return 0;
		} else {
			$db = DBClass::start();
			$sql = "SELECT * FROM ProjectTeams WHERE ".$db->arg("course_id", $this->id);
			$result = $db->select_count($sql);
			if (!$result){
				return 0;
			} else {
				return $result;
			}
		}
	}
	
	/**
	 * Returns the number of evaluations sent to a course
	 * 
	 * @param int $course course id
	 * @return int the number of evaluations sent to a course
	 * @author Lauren Taglieri
	 **/
	public function num_evaluations($course){
		if (!$this->solid()){
			//no evaluation, because this class doesn't even exist in the database
			return 0;
		} else {
			$db = DBClass::start();
			$sql = "SELECT SurveyInstances.id FROM SurveyInstances, Responses, ProjectStudents, ProjectTeams WHERE ".$db->arg("SurveyInstances.id", "Responses.survey_instance_id") ." AND " .$db->arg("ProjectStudents.id", "SurveyInstances.projectstudent_id") ." AND " .$db->arg("ProjectTeams.id", "ProjectStudents.projectteam_id") ." AND " .$db->arg("ProjectTeams.course_id", $course); 
			$result = $db->select_count($sql);
			if (!$result){
				return 0;
			} else {
				return $result;
			}
		}
	}
}

?>
