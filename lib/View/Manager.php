<?php
class Manager {
   /**
	 * Checks to see if a noticeflash is set, if not creates an array for noticeflash and adds $noticetext to that array 
	 *
	 * @param string $noticetext the notice message to be display
	 * @author Ari Rubinstein
	 **/
	public static function add_notice($noticetext){
		if (!isset($_SESSION['noticeflash']))
			$_SESSION['noticeflash'] = array();
		$_SESSION['noticeflash'][] = $noticetext;
	} 

	/**
	 * Checks to see if an errorflash is set, if not creates an array for errorflash and adds $errortext to that array.
	 *
	 * @param string $errortext the error message to be display
	 * @author Ari Rubinstein
	 **/
	public static function add_error($errortext){
		if (!isset($_SESSION['errorflash']))
			$_SESSION['errorflash'] = array();
		$_SESSION['errorflash'][] = $errortext;
	}
	
	/**
	 * Checks to see the existance of a request variable.  If it does not exist
	 * this will show a fatal error message.  Otherwise, returns the value.
	 *
	 * @return string the variable if it was set
	 * @author Ari Rubinstein
	 **/
	public static function require_variable($variablename){
		if (!isset($_REQUEST[$variablename]))
			Manager::fatal("$variablename must be provided");
		return $_REQUEST[$variablename];
	}
	
	
	/**
	 * Checks to see if the provided request variable was an int.  If not
	 * this throws an error, otherwise it returns the casted int.
	 * 
	 * @return integer the casted integer of the variable from the request array
	 * @author Ari Rubinstein
	 **/
	public static function require_variable_int($variablename){
		$v = Manager::require_variable($variablename);
		if (!Validation::valid_positive_integer($v))
			Manager::fatal("$variablename must be an integer");
		return (int)$v;
	}
		
	/**
	 * Checks to see if user can access the page, if not adds a fatal error message
	 *
	 * @param int $userlevel for type of user, string $errormessage the error message to be display
	 * @author Ari Rubinstein
	 **/
	public static function protect($userlevel, $errormessage = "You do not have access to this page"){
		if (!User::can_access($userlevel)){
			if (User::logged_in()){
				Manager::fatal($errormessage);
			} else {
				$dataArr = base64_encode(serialize(array("message" => $errormessage, "from" => $_SERVER['REQUEST_URI'])));
				header("Location: /home?data=$dataArr");
			}
			exit();
		}
	}
	
	/**
	 * Displays the fatal error message and directs to /404
	 *
	 * @param string $errormessage the error message to be display
	 * @author Ari Rubinstein
	 **/
	public static function fatal($errormessage){
		$page = new Template(TEMPLATE_DIRECTORY."index.tmpl.php");
		$page->set("title", "An Error has occurred");
		$page->set("content", new Partial("errormessage", array("controller" => $_REQUEST['controller'], "action" => $_REQUEST['action'], "message" => $errormessage)));
		echo $page->fetch();
		exit();
	}
}
?>