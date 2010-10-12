<?php
/** 
* ResponseObject Class
* @author Ari Rubinstein
* @package QuickEval
*/
require_once("config.php");

class ResponseForUserObject {
	public $type;
	public $user_for;
	public $responses = array();
	public function addResponse($responseobj){
		$this->responses[] = $responseobj;
	}
	
	public function ResponseForUserObject($type, $user_for){
		$this->type = $type;
		$this->user_for = $user_for;
	}
	
	public function shuffleResponses(){
		shuffle($this->responses);
	}
	
	public function isNumericResponse(){
		return ($this->type == Question::Q_NUMERICINPUT || $this->type == Question::Q_RATING);
	}
	
	public function getMinimum(){
		if (!$this->isNumericResponse()) return null;
		$values = array();
		foreach ($this->responses as $r)
			if ($r->getNumericValue() != null) $values[] = $r->getNumericValue();
		if (count($values) == 0) return null;
		else return min($values);
		
	}
	public function getMaximum(){
		if (!$this->isNumericResponse()) return null;
		$values = array();
		foreach ($this->responses as $r)
			if ($r->getNumericValue() != null) $values[] = $r->getNumericValue();
		if (count($values) == 0) return null;
		else return max($values);
	}
	public function getAverage(){
		if (!$this->isNumericResponse()) return null;
		$values = array();
		foreach ($this->responses as $r)
			if ($r->getNumericValue() != null) $values[] = $r->getNumericValue();
		if (count($values) == 0) return null;
		else return round(array_sum($values)/count($values),2);
	}
		
}

class ResponseObject {
	public $type;
	public $user_from;
	public $value;
	public $choice;
	public $comment;
	
	public function ResponseObject($type, $user_from, $value = null, $comment = null, $choice = null){
		$this->type = $type;
		$this->user_from = $user_from;
		$this->value = $value;
		$this->comment = $comment;
		$this->choice = $choice;
	}
	
	public function getNumericValue(){
		if ($this->type == Question::Q_RATING) return $this->choice;
		if ($this->type == Question::Q_NUMERICINPUT) return $this->value;
		return null;
	}
	
	public function hasValue(){
		return $this->value != null;
	}
	
	public function hasComment(){
		return $this->comment != null;
	}
	
	public function getString(){
		if (!isset($this->value)) return "No Response";
		switch($this->type){
			case Question::Q_RATING:
				return $this->value . " (".$this->choice.")";
				break;
			case Question::Q_SHORTANSWER:
				return $this->value;
				break;
			case Question::Q_MULTIPLECHOICE:
				return $this->value;
				break;
			case Question::Q_NUMERICINPUT:
				return $this->value;
				break;
			case Question::Q_CHECKBOXES:
				return implode($this->value, ", ");
				break;
			
			
		}
	}
	
}
?>