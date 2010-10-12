<?php
/** 
* Question Class
* @author Lauren Taglieri
* @package QuickEval
*/
require_once("config.php");

class Question extends CustomClass {
	const Q_RATING = 1;
	const Q_SHORTANSWER = 2;
	const Q_MULTIPLECHOICE = 3;
	const Q_NUMERICINPUT = 4;
	const Q_CHECKBOXES = 5;
	
	protected function allowed_parameters(){
		return array('id', 'owner_id', 'type', 'weight', 'data', 'list_order', 'survey_id', 'active');
	}
	protected function required_parameters(){
		return array('owner_id', 'type', 'weight', 'data', 'list_order', 'survey_id', 'active');
	}
	protected function default_parameters(){
		return array('owner_id' => User::current_user_id(), 'active' => 1, 'list_order' => 1, 'weight' => 1.0);
	}
	public function tablename(){ return "Questions";	}

	public function validate_parameter($name, $value){
		$errors = array();
		switch ($name){
			case "id":
				if (!Validation::validate_id($value))
					$errors[] = "ID must be greater than 0";
				break;
			case "list_order":
				if (!Validation::validate_id($value))
					$errors[] = "List order must be greater than 0";
				break;
			case "owner_id":
				if (!Validation::validate_fk($value))
					$errors[] = "Owner Id must be greater than 0";
				break;
			case "survey_id":
				if (!Validation::validate_fk($value))
					$errors[] = "survey Id must be greater than 0";
				break;
			case "type":
				if (!Validation::validate_numeric_range($value, 1, 5))
					$errors[] = "Question type must be specified between 1 and 5";
				break;
			case "active":
				if (!Validation::validate_numeric_range($value, 0, 1))
					$errors[] = "Active must be either 0 or 1";
				break;
			case "weight":
				if (!(is_numeric($value)) && $value < 0)
					$errors[] = "Weight must be a number greater than 0";
				break;
			case "data":
				if (!is_object(unserialize(base64_decode($value))))
					$errors[] = "data must not be blank";
				break;
			default:
				return $errors;
		}
		return $errors;
	}
	
	/**
	 * Returns the partial for the specified question type
	 *     
	 * @param array $response the response for the question, boolean $readonly, string $name_form_is_being_filled_out_for group member name
	 * @return partial for the specified question type
	 * @author Ari Rubinstein
	 **/
	public function render($response = array(), $readonly = false, $name_form_is_being_filled_out_for = "this group member", $user_for = null){
		$d = unserialize(base64_decode($this->data));
		if ($user_for == null){
			$theformname = "q_".$this->id;
		} else {
			$theformname = "q_".$this->id."_u_".$user_for;
		}
		$arr = array("name" => str_ireplace("%name%", $name_form_is_being_filled_out_for, $d->name), "form_name" => $theformname, "readonly" => $readonly, "response" => $response, "choices" => $d->choices);
		switch ($this->type){
			case self::Q_RATING:
				return new Partial("questions/rating", $arr);
				break;
			case self::Q_SHORTANSWER:
				return new Partial("questions/shortanswer", $arr);
				break;
			case self::Q_MULTIPLECHOICE:
				return new Partial("questions/multiplechoice", $arr);
				break;
			case self::Q_NUMERICINPUT:
				return new Partial("questions/numericinput", $arr);
				break;
			case self::Q_CHECKBOXES:
				return new Partial("questions/checkboxes", $arr);
				break;
			
		}
	}
}

?>
