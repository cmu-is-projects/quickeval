<?php
/** 
* SurveyInstance Class
* @author Tracy O'Connor
* @package QuickEval
*/
require_once("config.php");

class SurveyInstance extends CustomClass {
	/**
	 * Class variable for Complete status
	 **/
	
	const SI_COMPLETED = 2;
	/**
	 * Class variable for Incomplete status
	 **/
	const SI_INCOMPLETE = 1;

	/**
	 * Class variable for a completely incomplete survey instance
	 **/
	const SI_COMPLETELYINCOMPLETE = 0;
	
	/**
	 * Class variable if survey instance is open
	 **/
	const SI_OPEN = true;

	/**
	 * Class variable if survey instance is closed
	 **/
	const SI_CLOSED = false;
	
	
	protected function allowed_parameters(){
		return array('id', 'survey_id', 'projectteam_id', 'owner_id', 'date_given', 'closing_date', 'reminder_sent', 'survey_visibility_after_close', 'timestamp');
	}
	protected function required_parameters(){
		return array('survey_id','projectteam_id', 'owner_id');
	}
	protected function default_parameters(){
		return array('owner_id' => User::current_user_id(), 'survey_visibility_after_close' => 0);
	}
	public function tablename(){ return "SurveyInstances";	}

	public function validate_parameter($name, $value){
		$errors = array();
		switch ($name){
			case "id":
				if (!Validation::validate_id($value))
					$errors[] = "ID must be greater than 0";
				break;
			case "survey_id":
				if (!Validation::validate_fk($value))
					$errors[] = "Survey Id must be greater than 0";
				break;
			case "owner_id":
				if (!Validation::validate_fk($value))
					$errors[] = "Owner Id must be greater than 0";
				break;
			case "projectteam_id":
				if (!Validation::validate_fk($value))
					$errors[] = "projectteam id must be greater than 0";
				break;
			case "date_given":
				if (!Validation::validate_presence_of($value))
					$errors[] = "Date given must not be blank";
				break;
			case "closing_date":
				if (!Validation::validate_presence_of($value))
					$errors[] = "Closing date must not be blank";
				break;
			case "reminder_sent":
				if (!Validation::validate_numeric_range($value, 0, 1))
					$errors[] = "Reminder Sent can only be 1 or 0";
				break;
			default:
				return $errors;
		}
		return $errors;
	}
	
	
	public function is_visible_after_close(){
		if ($this->survey_visibility_after_close == 2){
			return false;
		} else {
			return true;
		}
	}
	
	
	/**
	 * checks to see if a student ID has completed a survey for another student
	 * and to what level they have completed that survey
	 *
	 * Steps for this lovely wonder
	 * 1) Find questions that this surveyinstance references
	 * 2) Find responses for each question
	 * 3) If number of responses != number of questions, then... incomplete
	 *  
	 * @param integer $student_id the student ID to check completion for
	 * @param integer $student_for the student ID to check if survey is complete
	 * @return integer the completion level of the survey (See class constants in SurveyInstance)
	 * @author Ari Rubinstein
	 **/
	public function has_student_completed_for($student_id, $student_for, $questions = null){
		if ($questions == null){//get questions
			$q_f = new Question();
			$questions = $q_f->find(array("survey_id" => $this->survey_id));
			if (!$questions){
				//there are no questions for the survey, they have by default filled out a blank survey
				return SurveyInstance::SI_COMPLETED;
			}
		}

		$r_f = new Response();
		$completedquestions = 0;
		foreach ($questions as $question){
			//find response
			$response = $r_f->findOne(array("survey_instance_id" => $this->id, "user_for" => $student_for, "user_id" => $student_id,"question_id" => $question->id));
			if ($response){
				$completedquestions++;
			}
		}
		if ($completedquestions == count($questions)){
			//all questions were completed
			return SurveyInstance::SI_COMPLETED;
		} else if ($completedquestions == 0){
			//no questions were completed
			return SurveyInstance::SI_COMPLETELYINCOMPLETE;
		} else {
			//some questions were completed
			return SurveyInstance::SI_INCOMPLETE;
		}
	}

	/**
	 * Finds if a student has completely filled out their survey for all team members
	 * 1) Find projectteam through projectteam_id
	 * 2) Find teammates through projectteam
	 * 3) For each teammember, see if they completed the survey for
	 *
	 * @param integer $student_id the student ID to check completion for
	 * @return integer the completion level of the survey (See class constants in SurveyInstance)
	 * @author Ari Rubinstein
	 **/
	public function has_student_completed_all($student_id){
		//find projectteam
		try {
			$projectteam = new ProjectTeam($this->projectteam_id);
		} catch (Exception $e){
			//error fetching projectteam
			return false;
		}
		//find projectstudents in projectteam
		$ps_f = new ProjectStudent();
		$projectstudents = $ps_f->find(array("projectteam_id" => $projectteam->id));
		if (!$projectstudents){
			//empty project group
			return false;
		}
		$completenesscounter = 0;
		
		$q_f = new Question();
		$questions = $q_f->find(array("survey_id" => $this->survey_id));
		if (!$questions){
			//there are no questions for the survey, they have by default filled out a blank survey
			return SurveyInstance::SI_COMPLETED;
		}

		foreach ($projectstudents as $projectstudent){
			if ($projectstudent->user_id == $projectstudent) continue; //we are ourselves. skip.
			$result = $this->has_student_completed_for($student_id, $projectstudent->user_id, $questions);
			if ($result == SurveyInstance::SI_COMPLETED){
				$completenesscounter++;
			}
		}
		
		if ($completenesscounter == count($projectstudents)-1){
			return SurveyInstance::SI_COMPLETED;
		} else if ($completenesscounter == 0){
			return SurveyInstance::SI_COMPLETELYINCOMPLETE;
		} else {
			return SurveyInstance::SI_INCOMPLETE;
		}
	}
	
	public function formatted_date_given(){
		if (is_numeric($this->date_given)){
			return date("M j, Y", $this->date_given); 
		}
		return date("M j, Y", strtotime($this->date_given));
	}
	
	public function formatted_date_due(){
		if (is_numeric($this->closing_date)){
			return date("M j, Y", $this->closing_date); 
		}
		return date("M j, Y", strtotime($this->closing_date));
		
	}
	
	public function set_closing_date($month, $day, $year){
		$this->closing_date = strtotime("$month/$day/$year 11:59:00 PM");
	}

	/**
	 * Checks to see if the survey instance is open
	 *
	 * @return boolean for if survey_instance is open
	 * @author Ari Rubinstein
	 **/
	public function isOpen(){ 
		if (time() - strtotime($this->closing_date) > 0){
			return SurveyInstance::SI_CLOSED;
		} else {
			return SurveyInstance::SI_OPEN;
		} 
		
	}	
	
	public function addOneDay(){
		$ONEDAY = (60*60*24);
		$reference = time();
		if (strtotime($this->closing_date) < time()){
			//use current time as reference
			$reference = time();
		} else {
			//use current closing date as reference.
			$reference = strtotime($this->closing_date);
		}
		$this->set_closing_date(date("m", $reference+$ONEDAY), date("d", $reference+$ONEDAY), date("Y", $reference+$ONEDAY));
	}             


	public function getResults($projectteam_id){
		
		try {
				$surveyinstance = $this;
				$projectteam = new ProjectTeam($projectteam_id);
			
				$survey = new Survey($surveyinstance->survey_id);
				$ps_f = new ProjectStudent();
			
				$projectstudents = $ps_f->find(array("projectteam_id"=>$projectteam->id));
				$course = new Course($projectteam->course_id);
				if (!$projectstudents) return null;
			
				$students = array();
				foreach ($projectstudents as $projectstudent){
					$students[] = new User($projectstudent->user_id);
				}
			
				$q_f = new Question();
				$shownames = true;
				if (User::can_access(User::U_TEACHER)){
					if (!$projectteam->isOwner()) Manager::fatal("You do not own that Project Team, therefore you can't view results for them");
					if (!$surveyinstance->isOwner()) Manager::fatal("You do not own that Survey Instance, therefore you can't view results for it");
					$shownames = true;
				} else if (User::can_access(User::U_STUDENT)){
					if ($surveyinstance->isOpen()) Manager::fatal("That survey has not closed yet.  Results can not be displayed");
								
					$ps_f = new ProjectStudent();
					$ps = $ps_f->findOne(array("projectteam_id" => $projectteam->id, "user_id" => User::current_user_id()));
					if ($surveyinstance->projectteam_id != $projectteam->id) Manager::fatal("You can't browse other survey instances that are not part of your project");
					if (!$ps) die("You are not in that project group, therefore you can't see the results");
					$shownames = false;
				}
				$responsepartialarray = array();
				$questions = $q_f->find(array("survey_id" => $survey->id), "list_order");
				if (!$questions) Manager::fatal("That survey instance has no questions in it.");
			
				$r_f = new Response();
				$c_f = new Comment();
				$count = 1;
				//loop through questions and find results
			
				foreach ($questions as $question){
					$qdata = unserialize(base64_decode($question->data));
					$question_name = str_replace("%name%", "this partner", $qdata->name);
					$question_choices = $qdata->choices;
					$bigresponse = new ResponseForUserObject($question->type, User::get_current_user());
				
					$responses = array();
					foreach ($students as $student_for){
						$r_o = new ResponseForUserObject($question->type, $student_for);
						foreach ($students as $student){
							$response = $r_f->findOne(array("user_id" => $student->id, "user_for" => $student_for->id, "survey_instance_id" => $surveyinstance->id, "question_id" => $question->id));
							$roo_o = new ResponseObject($question->type, $student);
							if ($response){
								$qd = unserialize(base64_decode($response->value));
								$roo_o->value = $qd->choices;
								$c = $c_f->findOne(array("response_id" => $response->id));
								if ($c)	$roo_o->comment = base64_decode($c->detail);
								if ($question->type == Question::Q_RATING){
									$roo_o->choice = array_search($qd->choices, $question_choices);
								
								}
							}
							$r_o->addResponse($roo_o);
							if ($r_o->isNumericResponse()) $bigresponse->addResponse($roo_o);
						}
						if (!$shownames) $r_o->shuffleResponses();
					
						$responses[] = $r_o;
					}
				
					$responsepartialarray[$count++] = array(
						"name" => $question_name,
						"responses" => $responses,
						"isNumeric" => false);
						
					if ($bigresponse->isNumericResponse()){
						$responsepartialarray[$count-1]['bigresponse'] = $bigresponse;
						$responsepartialarray[$count-1]['isNumeric'] = true;
					}
				}
		} catch (Exception $e){
			Manager::fatal("Could not load Survey Instance or ProjectTeam");
		}

		return array("responses" => $responsepartialarray, "course" => $course, "projectteam" => $projectteam, "survey" => $survey, "students" => $students, "shownames" => $shownames);
		
	}

}

?>
