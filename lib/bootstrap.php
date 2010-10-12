<?php
/**
 * This file includes all class files and libraries for the QuickEval site
 *
 * @author Ari Rubinstein
 * @version 1
 * @package QuickEval
 **/

//Class that everything extends
require_once("Util/Validation.php");
require_once("DBClass.php");
require_once("CustomClass.php");
require_once("View/Manager.php");
require_once("View/Template.php");
require_once("Util/Text.php");
require_once("Util/Gravatar.php");
require_once("Util/JSON.php");
require_once("Util/phpmailer.php");
require_once("Util/smtp.php");
require_once("Util/QuickEvalEmail.php");
require_once("Util/SCHelper.php");
require_once("Util/Sanitize.php");

require_once("User.php");
require_once("Course.php");
require_once("Comment.php");
require_once("Question.php");
require_once("QuestionData.php");
require_once("ResponseObject.php");
require_once("Response.php");

require_once("University.php");
require_once("ProjectStudent.php");
require_once("ProjectTeam.php");
require_once("Survey.php");
require_once("SurveyInstance.php");

session_name("quickevaldev");
@session_start();

?>