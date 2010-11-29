<?php
/** 
* User Class
* @author Ari Rubinstein
* @package QuickEval
*/
require_once("config.php");

class User extends CustomClass {
	const U_ADMIN = 3;
	const U_TEACHER = 2;
	const U_STUDENT = 1;
	const U_INVITED = 0;

	protected function default_parameters(){
		return array("active" => 1, "level" => 1, "university_id" => -1);
	}
	protected function required_parameters(){
		return array('university_id', 'level', 'email', 'active', 'password');
	}
	protected function allowed_parameters(){
		return array('id', 'university_id', 'fname', 'lname', 'level', 'email', 'invite_code', 'password', 'active', 'timestamp', 'last_login_ip');
	}
	public function tablename(){ return "Users"; }
	public function validate_parameter($name, $value){
		$errors = array();
		switch ($name){
			case "email";
				if (!Validation::validate_email($value))
					$errors[] = "eMail address must be a valid email address";
				if (!isset($this->id)){
					$temp = new User();
					$search = array("email" => $value);
					if ($temp->findOne(array("email" => $value)) !== false)
						$errors[] = "eMail Address must be unique";
				}
				break;
			case "id":
				if (!Validation::validate_id($value))
					$errors[] = "ID must be greater than 0";
				break;
			case "university_id":
				if (!Validation::validate_fk($value))
					$errors[] = "University ID must be greater than 0, or -1";
				break;
			case "level":
				if (!Validation::validate_numeric_range($value, -1, 4)) 
					$errors[] = "Level must be between -1 and 4";
				break;
			case "fname":
				if (!Validation::validate_presence_of($value))
					$errors[] = "First Name must not be blank";
				break;
			case "lname":
				if (!Validation::validate_presence_of($value))
					$errors[] = "Last Name must not be blank";
				break;
				
			default:
				return $errors;
		}
		return $errors;
	}
	
	/**
	 * Checks that session user is set and returns the id of the current user
	 * 
	 * @return int for current user's id or -1 if no user is logged in
	 * @author Ari Rubinstein
	 **/
	public static function current_user_id(){
		if (isset($_SESSION['current_user']) && $_SESSION['current_user'] != null)
			return $_SESSION['current_user']->id;
		return -1;
	}

	/**
	 * Gets the current user object
	 * @return Object the user object, or null if there is none
	 * @author Ari Rubinstein
	 **/
	public static function get_current_user(){
		if (isset($_SESSION['current_user']) && $_SESSION['current_user'] != null)
			return $_SESSION['current_user'];
		return null;
	}
	
	/**
	 * Checks that password is valid length
	 * 
	 * @param string $pass password that was entered  
	 * @return boolean true if password is valid
	 * @author Ari Rubinstein
	 **/
	public static function valid_password($pass){
		return Validation::validate_string_minimum_length($pass, 6);
	}     
	
	/**
	 * If email is set, changes all letters in email to lowercase
	 *
	 * @author Ari Rubinstein
	 **/
	protected function pre_save(){
		if (isset($this->email)){
			$this->email = strtolower($this->email);
		}
	}	
	
	/**
	* Returns a formatted name of the user object    
	* 
	* @return string the first name and last name separated by a space
	**/
	public function name(){
		return $this->fname . " " . $this->lname;
	}
	
	/**
	 * Returns the first name and the last initial
	 *
	 * @return string the first name and the last initial
	 * @author Ari Rubinstein
	 **/
	public function shortname(){
		return $this->fname . " " . strtoupper(substr($this->lname, 0, 1));
	}
	
	/**
	 * Returns the two capitalized Initials for the user
	 *
	 * @return string the two capitalized Initials for the user
	 * @author Ari Rubinstein
	 **/
	public function initials(){
		return strtoupper(substr($this->fname, 0, 1).substr($this->lname,0,1));
	}
	
	public function htmlAcronym(){
		return "<acronym title=\"".$this->name()."\">".$this->initials()."</acronym>";
	}
	
	/**
	 * Returns true if password is correct
	 * Returns false if password is not correct
	 *
	 * @param string $inpassword the clear-text password to be encrypted
	 * @return boolean true if password is correct
	 * @author Ari Rubinstein
	 **/
	public function check_password($inpassword){		
		return hash("sha256", "QKEV".$inpassword) == $this->password;
	}
	
	/**
	 * Returns a crypted password from the input
	 *
	 * @param string $inpassword the clear-text password to be encrypted
	 * @return string the crypted password
	 * @author Ari Rubinstein
	 **/
	public static function crypt_password($inpassword){
		return hash("sha256", "QKEV".$inpassword);
	}
		
	/**
	 * Changes the user's password to the encrypted password. Still need to save() user.
	 *   
	 * @param string $inpassword the clear-text password to be set 
	 * @author Ari Rubinstein
	 **/
	public function set_password($inpassword){
		$this->password = User::crypt_password($inpassword);
	}
	
	/**
	 * Returns boolean for if user login information is valid.   
	 * 
	 * @param string $username, $password  
	 * @return boolean true if valid password and email. 
	 * @author Ari Rubinstein
	 **/
	public static function login($username, $password){
		if (!Validation::validate_email($username)){
			Manager::add_error("Invalid Email Address");
			return false;
		}
		$us = new User();
		$u = $us->findOne(array("email" => $username));
		
		if (!$u){
			//no user with that email
			return false;
		} else {
			if ($u->level < User::U_STUDENT)
				return false;
			
			if ($u->check_password($password)){
				$u->last_login_ip = $_SERVER['REMOTE_ADDR'];
				$u->save();
				$_SESSION['logged_in'] = true;
				$_SESSION['current_user'] = $u;
				return true;
			} else {
				return false;
			}
		}
	}
	
	/**
	 * Returns true if user is logged in. 
	 * Returns false if user is not logged in. 
	 * 
	 * @return boolean true if current_user is set for the session 
	 * @author Ari Rubinstein
	 **/	
	public static function logged_in(){
		return isset($_SESSION['current_user']);
	}  
	  
	/**
	 * Returns true user is authorized to view page
	 * Returns false user is not authorized to view page
	 * 
	 * @param int $user_level_required 
	 * @return boolean true if user is authorized
	 * @author Ari Rubinstein
	 **/
	public function authorize($user_level_required){
		if ($this->level >= $user_level_required)
			return true;
		return false;
	}
	
	/**
	 * Returns a random invite code (string). 
	 * 
	 * @return random string 
	 * @author Ari Rubinstein
	 **/
	public function randomize_invite_code(){
		$this->invite_code = Text::random_string(40);
	}
	
	/**
	 * Sends welcome email to user   
	 * 
	 * @param object $userwelcoming welcome content of email
	 * @param object $courseobj the course object that the user was invited from
	 * @author Ari Rubinstein
	 **/
	public function welcome_user($userwelcoming, $courseobj){
		return QuickEvalEmail::SendSignupEmail($this, $userwelcoming, $courseobj);
	}
	
   	/**
	 * Returns true if the current user is allowed access.
	 * Returns false if the current user is not allowed access.  
	 * 
	 * @param int $userlevel access level of user (0 = not logged in, 1 = student, 2 = teacher, 3 = admin)
	 * @return boolean true if user can access
	 * @author Ari Rubinstein
	 **/
	public static function can_access($userlevel){
		if (!isset($_SESSION['current_user']) && $userlevel == 0){
			//if user isnt logged in
			return true;
		} else if (!isset($_SESSION['current_user']) && $userlevel > 0){
			//if user isnt logged in but userlevel is > 0
			return false;
		} else if (is_object($_SESSION['current_user']) && $_SESSION['current_user']->level >= $userlevel){
			return true;
		} else {
			return false;
		}
	}
		
}
?>