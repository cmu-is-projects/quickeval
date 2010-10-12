<?php
/** 
* Survey Class
* @author Tracy O'Connor
* @package QuickEval
*/
require_once("config.php");

class Survey extends CustomClass {
	protected function allowed_parameters(){
		return array('id', 'owner_id', 'name', 'description', 'modified_at', 'active');
	}
	protected function required_parameters(){
		return array('name', 'owner_id', 'active');
	}
	protected function default_parameters(){
		return array('owner_id' => User::current_user_id(), 'active' => 1);
	}
	public function tablename(){ return "Surveys";	}

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
				break;
			case "description":
				if (!Validation::validate_presence_of($value))
					$errors[] = "Description must not be blank";
				break;
			case "modified_at":
				if (!Validation::validate_presence_of($value))
					$errors[] = "Timestamp must not be blank";
				break;
			default:
				return $errors;
		}
		return $errors;
	}
	
	/**
	 * Returns the number of survey questions in the survey
	 *    
	 * @return int $result the number of questions
	 * @author Ari Rubinstein
	 **/
	public function num_survey_questions(){
		if (!$this->solid()){
			//no survey_questions, because this survey doesn't even exist in the database
			return 0;
		} else {
			$db = DBClass::start();
			$sql = "SELECT * FROM SurveyQuestions WHERE ".$db->arg("survey_id", $this->id);
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
