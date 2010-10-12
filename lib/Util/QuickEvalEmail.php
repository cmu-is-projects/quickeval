<?php
/**
 * Helper class for sending an email
 *
 * @package QuickEval
 * @author Ari Rubinstein
 **/
class QuickEvalEmail {

	/**
	 * Sends email to user
	 *
	 * @param string $sendTo the Email address to send Email, @param string $subject the subject line text, @param string $content the Email content
	 * @return boolean if did not send
	 * @author Ari Rubinstein
	 **/
	
	public static function SendEmail($sendTo, $subject, $content){
		$mail = new PHPMailer();
		/*if (ENVIRONMENT == "Production"){
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->Host = "smtp.gmail.com";
			$mail->Username = "quickeval@gmail.com";
			$mail->Password = "quickevalisamazing";
			$mail->SMTPSecure = 'ssl';
			$mail->Port = 465;
		}*/
		$mail->IsMail();
		
		$mail->From = "quickeval@gmail.com";
		$mail->FromName = "QuickEval";
		
		$mail->Subject = "[QuickEval] " . $subject;
		$emailhtml = new Partial("email/core", array("content" => $content));
		$mail->IsHTML(true);
		$mail->Body = $emailhtml->fetch();
		$mail->AltBody = strip_tags($content->fetch());
		$mail->AddAddress($sendTo);

		if (!$mail->Send()){
			return false;
		}
		return true;
	}
	
	/**
	 * Returns an constructed email paragraph template with the title and content set to the provided arguments
	 *
	 * @param string $title the title to put in the email
	 * @param object $content the content template to put in the email
	 * @return object the constructed email paragraph template
	 * @author Ari Rubinstein
	 **/
	public static function EmailP($title, $content){
		return new Partial("email/paragraph", array("title" => $title, "content" => $content));
	}
	
	/**
	 * Sends an email that welcomes a new user to quickeval
	 *
	 * @param Object $newuser the user object to send the email to
	 * @param Object $welcominguser the user object that is sending the email
	 * @param Object $courseobj the course object of the course the user was added to
	 * @return boolean true if the email was successfully sent
	 * @author Ari Rubinstein
	 **/
	public static function SendSignupEmail($newuser, $welcominguser, $courseobj){
		return QuickEvalEmail::SendEmail($newuser->email, 
			"Welcome to QuickEval",
			QuickEvalEmail::EmailP("Welcome to QuickEval", 
				new Partial("email/activate", 
					array(
						"inviteduser"=> $newuser, 
						"invitinguser" => $welcominguser,
						"course" => $courseobj
					)
				)
			)
		);
	}

	public static function SendSurveyReminderEmail($student, $survey, $course){
		return QuickEvalEmail::SendEmail($student->email, 
			"Survey due for " . Text::onlyCharsAndNumbersAndSpace($course->name),
			QuickEvalEmail::EmailP("Evaluation is almost closed!", 
				new Partial("email/reminder", 
					array(
						"user"=> $student, 
						"course" => $course,
						"survey" => $survey
					)
				)
			)
		);
	}

	public static function SendNewManualAddEmail($user, $password){
		return QuickEvalEmail::SendEmail($user->email, 
			"Welcome to QuickEval",
			QuickEvalEmail::EmailP("Welcome!", 
				new Partial("email/profsignup_accountdetails", 
					array(
						"user"=> $user, 
						"password" => $password
					)
				)
			)
		);
	}

	public static function SendProfSignupInitiateEmail($email_address_of_signing_up_user){
		return QuickEvalEmail::SendEmail($email_address_of_signing_up_user, 
			"QuickEval professor application in review!", 
			QuickEvalEmail::EmailP("Application pending review!", new Partial("email/profsignup_initiate"))
		);
	}
	
	

	/**
	 * Sends a class-add email
	 *
	 * @return boolean true if the email was successfully sent
	 * @param Object $user the user object to send the email to
	 * @param Object $professor the user object that is sending the email
	 * @param Object $courseobj the course object of the course the user was added to
	 * @author Ari Rubinstein
	 **/
	public static function SendClassAddEmail($user, $professor, $courseobj){
		return QuickEvalEmail::SendEmail($user->email, 
			"You have been added to a course",
			QuickEvalEmail::EmailP("You have been added to a course", 
				new Partial("email/courseadd", 
					array(
						"inviteduser"=> $user, 
						"invitinguser" => $professor,
						"course" => $courseobj
					)
				)
			)
		);

	}
	
	/**
	 * Sends a new survey email
	 *
	 * @return boolean true if the email was successfully sent
	 * @param Object $user the user object to send the email to
	 * @param Object $professor the user object that is sending the email
	 * @param Object $courseobj the course object of the course the user was added to
	 * @author Ari Rubinstein
	 **/
	public static function SendNewSurveyEmail($user, $professor, $courseobj){
		return QuickEvalEmail::SendEmail($user->email, 
			"New peer evaluation for ".$courseobj->name,
			QuickEvalEmail::EmailP("Hello " . $user->name(), 
				new Partial("email/newsurvey", 
					array(
						"prof" => $professor,
						"course" => $courseobj
					)
				)
			)
		);

	}
	
	/**
	 * Sends a forgot password email
	 *
	 * @return boolean true if the email was successfully sent
	 * @param Object $user the user object to send the email to
	 * @author Ari Rubinstein
	 **/
	public static function SendNewResetPassword($user){
		return QuickEvalEmail::SendEmail($user->email, 
			"Reset Password Request ",
			QuickEvalEmail::EmailP("Hello " . $user->name(), 
				new Partial("email/resetpassword", 
					array(
						"user" => $user
					)
				)
			)
		);

	}
	
	
} // END class 


?>