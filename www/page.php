<?php
require_once("config.php");

switch ($_GET['controller']){
	case "fillsurvey":
		Manager::protect(User::U_STUDENT, "You must be logged in to access this page");
		$si_id = Manager::require_variable_int("si_id");
		$u_id = Manager::require_variable_int("u_id");
		$page = "evaluations";
		
		if ($u_id == User::current_user_id()){
			Manager::fatal("You can not fill out a survey for yourself");
		}
		//see if user can even fill out the survey
		$si_f = new SurveyInstance();
		$surveyinstance = $si_f->findOne(array("id"=>$si_id));
		if (!$surveyinstance){
			Manager::fatal("That surveyinstance doesn't exit.");
		}
		if (!$surveyinstance->isOpen()){
			Manager::fatal("That survey is closed, you can not fill a survey out for it");
		}
		
		//see if student is in given survey instances' project team
		$ps_f = new ProjectStudent();
		$projectstudent = $ps_f->findOne(array("user_id" => User::current_user_id(), "projectteam_id" => $surveyinstance->projectteam_id));
		if (!$projectstudent){
			Manager::fatal("You have not been assigned to that survey instance, therefore you can't fill it out.");
		}
		
		try {
			$s = new Survey($surveyinstance->survey_id);
			$q = new Question();
			$groupmembers = $ps_f->findOne(array("projectteam_id" => $surveyinstance->projectteam_id, "user_id" => $u_id));
			if (!$groupmembers){
				Manager::fatal("You can not fill out a survey for that member");
			}
			
			$questions = $q->find(array("survey_id" => $s->id), "list_order");
			$questionarr = array();
			$comments = array();
			$r_f = new Response();
			$userfor = new User($u_id);
			if ($questions){
				foreach ($questions as $qu){
					$response = $r_f->findOne(array("user_for" => $userfor->id, "question_id" => $qu->id, "survey_instance_id" => $surveyinstance->id, "user_id" => User::current_user_id()));
					if ($response){
						$value = unserialize(base64_decode($response->value));
						$choice = $value->choices;
						if (!is_array($choice)){
							$choice = array($choice);
						}
						try {
							$c_f = new Comment();
							$comment = $c_f->findOne(array("response_id" => $response->id));
							if ($comment){
								$comments["c_".$response->question_id] = base64_decode($comment->detail);
							}
						} catch (Exception $e){
							
						}
						
					} else {
						$choice = array();
					}
					$questionarr["q_".$qu->id] = $qu->render($choice, false, $userfor->name());
				}
			}

			$breadcrumb = array('Evaluations' => '/evaluations', "Completing Survey: " . $s->name => "");
			$title = "Filling out survey for ".$userfor->name();
			
		
		} catch (Exception $e){
			Manager::fatal("That survey does not exist");
		}
		
		$c = array(new Partial("grid", array("width" => 12, "title" => $title." <a href='http://en.gravatar.com/emails/'><img src='".Gravatar::getGravatarImageLocation($userfor->email, 40)."' alt='Avatar' style='margin-bottom: 2px;' /></a>", "content" => array(new Partial("forms/questions", array("survey_id" => $s->id, "instance_id" => $surveyinstance->id)), new Partial("questions/display", array("user_for" => $userfor, "comments" => $comments, "questions" => $questionarr, "instance_id" => $surveyinstance->id))))));

		
		
		
		
		
		break;
	case "evaluations":
		Manager::protect(User::U_STUDENT, "You must be logged in to access the Evaluations page");
		$title = "Evaluations";
		$page = "evaluations";
		if (User::can_access(User::U_TEACHER)){
			$c = array(new Partial("grid", array("width" => 12, "title" => "Evaluations", "button" => "Create an evaluation", "content" => new Partial("forms/survey"))));	
		} else if (User::can_access(User::U_STUDENT)){
			//show pending evaluations
			
			//find all groups I am part of
			$ps_f = new ProjectStudent();
			$myassociations = $ps_f->find(array("user_id" => User::current_user_id()));
			if (!$myassociations){
				$c = array(new Partial("grid", array("width" => 9, "title" => "Evaluations", "content" => "You have no evaluations to complete! yay!")));
			} else {
				$resultarr = array();
				foreach ($myassociations as $projectstudent){
					try {
						$detailarray = array();
						$projectteam = new ProjectTeam($projectstudent->projectteam_id);
						$course = new Course($projectteam->course_id);
						$detailarray['course'] = $course;
						$detailarray['group'] = $projectteam;

						//find all surveyinstances
						$si_f = new SurveyInstance();
						$instances = $si_f->find(array("projectteam_id" => $projectteam->id));
						$detailarray['instances'] = $instances;
						
						
						//find all students skipping me
						$students = $ps_f->find(array("projectteam_id" => $projectteam->id));
						$starr = array();
						$u_f = new User();
						foreach ($students as $student){
							$starr[] = $u_f->findOne(array("id" => $student->user_id));
						}
						$detailarray['students'] = $starr;

						$resultarr[] = $detailarray;
					} catch (Exception $e){
						//couldn't load projectteam
						continue;
					}
				}
				$c = new Partial("grid", array("width" => 12, "title" => "My Evaluations", "content" => new Partial("studentsurveyinstances", array("evalgroups" => $resultarr))));				
			}
		}
		break;
	case "mysettings":
		Manager::protect(User::U_STUDENT, "You must be logged in to access the my settings page");
		$title = "My Settings";
		$page = "mysettings";
		
		$c = array(new Partial("grid", array("width" => 12, "title" => "My Settings", "content" => new Partial("mysettings_form"))));
		break;
	case "questions":
		Manager::protect(User::U_TEACHER, "You must be a teacher to edit a survey");
		$title = "Viewing Evaluation";
		$page = "evaluations";
		$surveyid = Manager::require_variable_int("survey");
		try {
			$s = new Survey($surveyid);
			if ($s->owner_id != User::current_user_id()){
				Manager::fatal("You do not have permission to edit that survey");
			}
			$q = new Question();
			$questions = $q->find(array("survey_id" => $s->id), "list_order");
			$questionarr = array();
			if ($questions){
				foreach ($questions as $qu){
					$questionarr["q_".$qu->id] = $qu->render(array(), true);
				}
			}
	
		$breadcrumb = array('Evaluations' => '/evaluations', $s->name => "");
		$title = $s->name;
		
		} catch (Exception $e){
			Manager::fatal("That survey does not exist");
		}
		
		$c = array(new Partial("grid", array("width" => 12, "button" => "Add a question", "title" => new Partial("liveedit", array("controller" => "surveys", "action" => "editname", "value" => $s->name, "id" => $s->id)), "content" => array(new Partial("forms/questions", array("survey_id" => $s->id)), new Partial("questions/editor", array("questions" => $questionarr))))));
		break;
	case "courses":
		Manager::protect(User::U_TEACHER, "You must be a teacher to access the courses page");
		$title = "Courses";
		$page = "courses";
		$c = array(new Partial("grid", array(
		"width" => 12, 
		"title" => "Courses", 
		"button" => "Add a course",
		"content" => new Partial("forms/course")
		)));
		break;
	case "viewcourse":
		Manager::protect(User::U_TEACHER, "You must be a teacher to access the view course page");
		$courseid = Manager::require_variable_int("course");
		$page = "courses";
		try {
			$c = new Course($courseid);
			if (!$c->isOwner()){
				Manager::fatal("You do not own that course - you can't edit it");
				break;
			}
			
			$groups = array();
			$pt = new ProjectTeam();
			$project_teams = $pt->find(array("course_id" => $c->id));
			if ($project_teams){
				foreach ($project_teams as $ppp){
					$groups[$ppp->id] = array();
				}
			}

			foreach ($groups as $projectteam=>$g){
				$ps = new ProjectStudent();
				$projectstudents = $ps->find(array("projectteam_id" => $projectteam));
				if ($projectstudents){
					foreach ($projectstudents as $projectstudent){
						$groups[$projectteam][] = $projectstudent;
					}
				}
			}
		
			$breadcrumb = array('Courses' => '/courses', $c->name => "");
			$title = $c->name;
			
		} catch (Exception $e){
			Manager::fatal("Could not find that course");
		}
		$c = array(new Partial("grid", array(
		"width" => 12, 
		"title" => new Partial("liveedit", array("controller" => "courses", "action" => "rename", "value" => $c->name, "id" => $c->id)),
		"button" => "Add student(s)", "button2" => "Add a group", 
		"content" => new Partial("course", array("groups"=>$groups, "courseid" => $c->id))
		)));
		
		break;
	case "home":
		if (User::can_access(User::U_STUDENT)){
			//Students=evaluations
			//Teachers=results
			if (User::can_access(User::U_TEACHER)){
				//user is a teacher
				header("Location: /results");
				exit();
			} else {
				//user is a student
				header("Location: /evaluations");
				exit();
			}
		} else {
			//show barebones login
			$page = new Template(TEMPLATE_DIRECTORY."index.tmpl.php");
			$requestarr = array();
			if (isset($_REQUEST['data'])) {
				$requestarr = unserialize(base64_decode($_REQUEST['data']));
				if (isset($requestarr['message'])) Manager::add_error($requestarr['message']);
			}
			$page->set("content", new Partial("smallhome/loginbox", $requestarr));
			$page->set("title", "Login");
			echo $page->fetch();
			exit();
		}
		break;
	case "forgotpassword":
		$page = new Template(TEMPLATE_DIRECTORY."index.tmpl.php");
		$page->set("title", "Reset Password");
		$page->set("content", new Partial("smallhome/resetpasswordrequest"));
		echo $page->fetch();
		exit();
		break;
	case "resetpassword":
		$page = new Template(TEMPLATE_DIRECTORY."index.tmpl.php");
		$page->set("title", "Reset Password");
		$page->set("content", new Partial("smallhome/resetpassword"));
		echo $page->fetch();
		exit();
		break;	
	case "signup":
		$page = new Template(TEMPLATE_DIRECTORY."index.tmpl.php");
		$page->set("title", "Activate your account");
		$page->set("content", new Partial("smallhome/signup"));
		echo $page->fetch();
		exit();
		break;    
	case "profsignup":
		$page = new Template(TEMPLATE_DIRECTORY."index.tmpl.php");
		$page->set("title", "Sign Up");
		$page->set("content", new Partial("smallhome/profsignup"));
	    echo $page->fetch();
	   	exit();
	   	break;
	case "profsignup_confirm":
		Manager::protect(User::U_ADMIN, "You must be an admin to access this page");
		$page = new Template(TEMPLATE_DIRECTORY."index.tmpl.php");
		$page->set("title", "Add New User");
		$ps = new Partial("smallhome/profsignup_confirm");
		if (isset($_REQUEST['key'])){
			$data = unserialize(base64_decode($_GET['key']));
			$ps->set("first_name",$data['first']);
			$ps->set("last_name",$data['last']);
			$ps->set("email",$data['email']);
		}
		
		$page->set("content", $ps);
		echo $page->fetch();
		exit();
		break;
	case "results":
		Manager::protect(User::U_TEACHER, "You must be a teacher to access this page");
		$title = "Results";
		$page = "results";
		$pt_f = new ProjectTeam();
		if (isset($_GET['course'])){
			$projectteams = $pt_f->find(array("owner_id" => User::current_user_id(), "course_id" => (int)$_GET['course']));	
		} else {
			$projectteams = $pt_f->find(array("owner_id" => User::current_user_id()));
		}
		if (!$projectteams){
			//we have no project teams
			$c = new Partial("grid", array("width" =>12, "title" => "Welcome to QuickEval!", "content" => new Partial("welcome")));

		} else {
			$resultarr = array();
			foreach ($projectteams as $pt){
				//get course name
				//get group name
				try {
					$temp_c = new Course($pt->course_id);
					$tarr['course'] = $temp_c;
				} catch (Exception $e){
					$tarr['course']="Invalid Course";
					continue;
				}
				$tarr['group']=$pt;
				
				$ps_f = new ProjectStudent();
				$projectstudents = $ps_f->find(array("projectteam_id" => $pt->id));
				
				if (!$projectstudents){
					$tarr['students'] = null;
					continue; //no students in that group
				} else {
					$students = array();
					try {
						foreach ($projectstudents as $student){
							$students[] = new User($student->user_id);
						}
						$tarr['students'] = $students;
					} catch (Exception $e){
						$tarr['students'] = null;
					}
				}
				if (!isset($tarr['students'])){
					continue;
				}
				
				$ps_si = new SurveyInstance();
				$surveyinstances = $ps_si->find(array("projectteam_id" => $pt->id));
				$q_f = new Question();
				if (!$surveyinstances){
					$tarr['instances'] = null;
					continue; //no surveyinstances in that group

				} else {
					$tarr['instances'] = $surveyinstances;
				}
				
				$resultarr[] = $tarr;
			}
			
			$resultarr = array_reverse($resultarr);
			
			$c = new Partial("grid", array("width" => 12, "title" => "Results", "content" => new Partial("teacherresults", array("evalgroups" => $resultarr))));
		}
		
		break;
	case "24hourEmailReminder":
		set_time_limit(60*10); //10 minutes
		
		//find all surveyinstances that are going to expire tomorrow
		$configuration_key = "aa2kk2n1g230c8ugfy482";
		if ($_GET['access_token'] != $configuration_key){
			//trick user into thinking page doesn't exist / deter key guessing
			header("HTTP/1.0 404 Not Found");
			exit();
		}
		echo "Starting email..";
		$emailcount = 0;
		$si_f = new SurveyInstance();
		$instances = $si_f->findSQL("SELECT * FROM `SurveyInstances` WHERE DATE(closing_date) = CURDATE() AND reminder_sent = 0");

		if ($instances){
			foreach ($instances as $instance){
				//loop through all survey instances that are closing today.
				try {
					//grab survey data
					$survey = new Survey($instance->survey_id);
					//grab project team
					$projectteam = new ProjectTeam($instance->projectteam_id);
					//grab course
					$course = new Course($projectteam->course_id);
					FB::info($course);
					$ps_f = new ProjectStudent();
					$projectstudent = $ps_f->find(array("projectteam_id" => $instance->projectteam_id));
					if ($projectstudent){
						foreach ($projectstudent as $projectstu){
							$user = new User($projectstu->user_id);
							QuickEvalEmail::SendSurveyReminderEmail($user, $survey, $course);
							$emailcount++;
						}
					}
					$instance->reminder_sent = 1;
					$instance->save();
				} catch (Exception $e){
					Manager::fatal("Error getting objects");
				}
			}
			
		}
		echo "Sent $emailcount emails";
		exit;
		
		
		break;
	case "about":
		$title = "About Us";
		$page = "about";
		$c = new Partial("about");
		break;
	case "faq":
		$title = "Frequently Asked Questions";
		$page = "faq";
		$c = new Partial("faq");
		break;
	case "privacy":
		$title = "Privacy Policy";
		$page = "privacy";
		$c = new Partial("privacy");
		break;
	case "termsofuse":
		$title = "Terms of Use";
		$page = "termsofuse";
		$c = new Partial("termsofuse");
		break;
	case "contact":
		$title = "Contact Us";
		$page = "contact";
		$c = new Partial("contact");
		break;
	case "viewcrossinstances":
		Manager::protect(User::U_STUDENT, "You must be a teacher or a student to view the cross-instance results page");
		$page = "results";
		$title="Cross-Instance Results";
		if (!isset($_GET['instances'])) Manager::fatal("you must provide some instances");
		$instances = array();
		foreach (explode(",", $_GET['instances']) as $instance){
			$instances[] = (int)$instance;
		}
		if (count($instances) == 0) Manager::fatal("you must provide some instances");
		$projectteamid = Manager::require_variable_int("group");
		$results = array();
		$course = null;
		$shownames = null;
		$survey = null;
		$projectteam = null;
		$students = null;
		
		
		foreach ($instances as $instanceid){
			try {
				$surveyinstance = new SurveyInstance($instanceid);
				if (User::get_current_user()->level == User::U_STUDENT && !$surveyinstance->is_visible_after_close()){
					continue;
				}
				$results[] = $surveyinstance->getResults($projectteamid);
			} catch (Exception $e){
				Manager::fatal("Could not load Survey Instance or ProjectTeam");
			}
		}
		$breadcrumb = array($course->name => "/viewcourse?course=".$course->id, $survey->name => "/questions?survey=".$survey->id, $projectteam->name => "/viewcourse?course=".$course->id, "Cross-Instance Results" => "");
		if (count($results) == 0) Manager::fatal("Could not find any survey instances");
		$partialarr = array();
		$count = 0;
		$surveyinstancecount = 0;
		foreach ($results as $responses){
			//iterate through survey instances
			$shownames = $responses['shownames'];
			foreach ($responses['responses'] as $response){
				$details = array("name" => $response['name'], "responses" => $response['responses'], "shownames" => $shownames, "resultcount" => $count++, "isNumeric" => $response['isNumeric']);
				
				if ($response['isNumeric']){
					$details = array_merge($details, array("bigresponse" => $response['bigResponse']));
				}
				
				$p = new Partial("responses/groupresponse", $details);
				$partialarr[$surveyinstancecount][] = $p->fetch();
				
				if ($response['isNumeric'] == true){
					$p = new Partial("responses/mathsummary", array(
						"resultcount" => $count++,
						"responses" => $response['bigResponse']
						));
					$partialarr[$surveyinstancecount][] = $p->fetch();
				}
			}
			$surveyinstancecount++;
		}
		
		
		$c = new Partial("responses/crossinstanceresults", array("shownames" => $results[0]['shownames'], "survey" => $results[0]['survey'], "projectteam" => $results[0]['projectteam'], "instanceresults" => $partialarr, "projectteam" => $results[0]['projectteam'], "students" => $results[0]['students']));
		
		break;
	case "viewresults":
		Manager::protect(User::U_STUDENT, "You must be a student or a teacher to view the results page");
		$page = "results";
		$title = "Viewing Results";
		$instanceid = Manager::require_variable_int("instance");
		$projectteamid = Manager::require_variable_int("group");
		
		try {
			$surveyinstance = new SurveyInstance($instanceid);
			$surveyinstanceresults = $surveyinstance->getResults($projectteamid);
			
			$responses = $surveyinstanceresults['responses'];
			$course = $surveyinstanceresults['course'];
			$students = $surveyinstanceresults['students'];
			$shownames = $surveyinstanceresults['shownames'];
			$survey = $surveyinstanceresults['survey'];
			$projectteam = $surveyinstanceresults['projectteam'];
			
			
		} catch (Exception $e){
			Manager::fatal("Could not load Survey Instance or ProjectTeam");
		}
		
		if (User::get_current_user()->level == User::U_STUDENT && !$surveyinstance->is_visible_after_close()){
			Manager::fatal("Survey Results have been disabled for this survey by the course instructor");
		}

		//csv output - if we hit this, we quit afterwards
		if ($_GET['type'] == "csv"){
			Manager::protect(User::U_TEACHER, "You must be a teacher to download a csv");
			
			$c = new Partial("resultcsv", array("results" => $responses));
			
			$filename = Text::spacesToUnderscores("QuickEval Dump for ".Text::onlyCharsAndNumbersAndSpace(trim($survey->name))."  " . Text::onlyCharsAndNumbersAndSpace(trim($projectteam->name)) .".csv");

			//force download
			header("Pragma: public"); // required
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false); // required for certain browsers 
			header("Content-Type: text/csv");
			header("Content-Disposition: attachment; filename=\"".basename($filename)."\";" );
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".strlen($c->fetch()));
			echo $c->fetch();
			exit();
			
		} else if ($_GET['type'] == "instancesummary"){
			//instance summary page
			Manager::protect(User::U_TEACHER, "You must be a teacher to view the instance summary");
			$breadcrumb = array($course->name => "/viewcourse?course=".$course->id, $survey->name => "/questions?survey=".$survey->id, $projectteam->name => "/viewcourse?course=".$course->id, "Instance Summary" => "");
			$c = new Partial("responses/instancesummary", array("shownames" => $shownames, "survey" => $survey, "projectteam" => $projectteam, "responses" => $responses, "projectteam" => $projectteam, "students" => $students));
		} else {
			$breadcrumb = array($course->name => "/viewcourse?course=".$course->id, $survey->name => "/questions?survey=".$survey->id, $projectteam->name => "/viewcourse?course=".$course->id, "Composite Results" => "");
			$c = new Partial("responses/alldata", array("shownames" => $shownames, "projectteam" => $projectteam, "responses" => $responses, "survey" => $survey, "projectteam" => $projectteam, "students" => $students));
		}

		break;
	case "login":
		$loginuri = "/home";
		if (isset($_POST['from'])) $loginuri = "/home?data=".base64_encode(serialize(array("from" => $_POST['from'])));
		if ($_POST['email'] == ""){
			Manager::add_error("You need an email to log in");
			header("Location: $loginuri");
			exit();
		}
		if ($_POST['password'] == ""){
			Manager::add_error("You need a password to log in");
			header("Location: $loginuri");
			exit();
		}
		$email = $_POST['email'];
		$pass = $_POST['password'];
		if (User::login($email, $pass)){
			Manager::add_notice("Login Successful");
			if (isset($_POST['from'])){
				header("Location: ".$_POST['from']);
				exit();
			}
		} else {
			Manager::add_error("Login Unsuccessful");
		}
		header("Location: /");
		exit();
		break;
	case "logout":
		$_SESSION['logged_in'] = false;
		$_SESSION['current_user'] = null;
		session_destroy();
		session_name("quickevaldev");
		@session_start();
		Manager::add_notice("You have been successfully logged out");
		header("Location: /");
		exit();
		break;
	default:
		$title = "404";
		$page = "404";
		$c = new Partial("grid", array("width" =>8, "title" => "Page Can Not Be Found", "content" => "Sorry, but the page you have requested cannot be found."));
		break;
}


$tpage = new Template(TEMPLATE_DIRECTORY."core.tmpl.php");
$tpage->set("title", $title);

$header = new Template(TEMPLATE_DIRECTORY."header.tmpl.php");
$header->set("page", $page);
$content = new Template(TEMPLATE_DIRECTORY."content.tmpl.php");
$content->set("title", $title);
$content->set("sidebar", array(new Partial("sidebar")));
if (isset($breadcrumb)){
	$content->set("breadcrumb", $breadcrumb);
}
$content->set("content", $c);
$tpage->set("content", array($header->fetch(), $content->fetch()));

echo $tpage->fetch();

?>