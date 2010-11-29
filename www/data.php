<?php
require_once("config.php");
$output = array();

function protect($level){
	if (!User::can_access($level)){
		$x = array();
		$x['success'] = false;
		$x['errors'][] = "You do not have access to that feature";
		echo json_encode($x);
		exit();
	}
}

function searchResult($url, $image, $text, $description){
    return "<a href='$url'><img alt='' src='$image'/><span class='searchheading'>$text</span><span>$description</span></a>";
}

function hilightSearch($word, $subject) {
    $regex_chars = '\.+?(){}[]^$';
    for ($i=0; $i<strlen($regex_chars); $i++) {
        $char = substr($regex_chars, $i, 1);
        $word = str_replace($char, '\\'.$char, $word);
    }
    $word = '(.*)('.$word.')(.*)';
    return @eregi_replace($word, '\1<span class="highlight">\2</span>\3', $subject);
}
function errorsExist(){
	global $output;
	return (isset($output['errors']) && count($output['errors']) > 0);
}
function addError($error){
	global $output;
	if (is_array($error)){
		foreach ($error as $e){
			$output['errors'][] = $e;
		}
	} else {
		$output['errors'][] = $error;
	}
}

switch ($_REQUEST['controller']){	
	case "surveyinstances":
		switch ($_REQUEST['action']){
			case "extenddeadline":
				protect(User::U_TEACHER);
				$si_id = Manager::require_variable_int("instance_id");
				$g_id = Manager::require_variable_int("group_id");
				try {
					$surveyinstance = new SurveyInstance($si_id);
					if (!$surveyinstance->isOwner()){
						addError("You dont own that survey instance");
						break;
					}
					$group = new ProjectTeam($g_id);
					if (!$group->isOwner()){
						addError("You dont own that group instance");
						break;
					}
					$surveyinstance->addOneDay();
					$surveyinstance->reminder_sent = 0;
					if (!$surveyinstance->save()){
						addError("Error saving new surveyinstance");
					}
					$output['newdate'] = $surveyinstance->formatted_date_due();
					
				} catch (Exception $e){
					addError("Error extending deadline");
				}
				break;
			case "submit":
				protect(User::U_STUDENT);
				$si_id = Manager::require_variable_int("si_id");
				$u_id = Manager::require_variable_int("u_id");
				if ($u_id == User::current_user_id()){
					addError("You can not fill out a survey for yourself");
					break;
				}
				//see if user can even fill out the survey
				$si_f = new SurveyInstance();
				$surveyinstance = $si_f->findOne(array("id"=>$si_id));
				if (!$surveyinstance){
					addError("That surveyinstance doesn't exit.");
					break;
				}
				if (!$surveyinstance->isOpen()){
					addError("That survey is closed, you can not fill a survey out for it");
					break;
				}
		
				//see if student is in given survey instances' project team
				$ps_f = new ProjectStudent();
				$projectstudent = $ps_f->findOne(array("user_id" => User::current_user_id(), "projectteam_id" => $surveyinstance->projectteam_id));
				if (!$projectstudent){
					addError("You have not been assigned to that survey instance, therefore you can't fill it out.");
					break;
				}
		
				try {
					$s = new Survey($surveyinstance->survey_id);
					$q = new Question();
					$groupmembers = $ps_f->findOne(array("projectteam_id" => $surveyinstance->projectteam_id, "user_id" => $u_id));
					if (!$groupmembers){
						addError("You can not fill out a survey for that member");
						break;
					}
					$user_for = new User($u_id);
			
					$questions = $q->find(array("survey_id" => $s->id), "list_order");
					$questionarr = array();
			
					if ($questions){
						$responses = array();
						$comments = array();
						foreach ($_REQUEST as $name=>$val){
							if (substr($name, 0, 2) == "q_"){
								$responses[$name] = $val;
							}
							if (substr($name, 0, 2) == "c_"){
								$comments[$name] = $val;
							}
						}

						foreach ($questions as $question){
							$qd = unserialize(base64_decode($question->data));
							$questionstr = "Question ".str_replace("%name%", $user_for->name(), $qd->name);
						
							//first make sure that the question was submitted in the form
							if (!array_key_exists("q_".$question->id, $responses)){
								addError($questionstr." is required");
							}
							
							switch ($question->type){
								case Question::Q_CHECKBOXES:
								case Question::Q_MULTIPLECHOICE:
								case Question::Q_RATING:
									if (!is_array($responses["q_".$question->id]) && $responses["q_".$question->id]=="")
										addError("You must select at least one option for $questionstr");
									break;
								case Question::Q_SHORTANSWER:
									if (!Validation::validate_string_minimum_length($responses["q_".$question->id], 2))
										addError("You must type at least 2 characters for $questionstr");
									break;
								case Question::Q_NUMERICINPUT:
									if (!Validation::valid_positive_integer($responses["q_".$question->id]))
										addError($questionstr . " must be a numeric value");
									break;
							}
							
						}
					}
					$q_f = new Response();
					if (!errorsExist()){
						//create the responses and save them
						foreach ($responses as $questionid=>$response){
							$questionid_int = substr($questionid, 2);
							$previousquestion = $q_f->findOne(array("user_id" => User::current_user_id(), "survey_instance_id" => $surveyinstance->id, "question_id" => $questionid_int, "user_for" => $user_for->id));
							if (!$previousquestion){
								//we make a new one
								$q = new Response();
							} else {
								$q = $previousquestion;
							}
							$q->user_id = User::current_user_id();
							$q->survey_instance_id = $surveyinstance->id;
							$q->question_id = $questionid_int;
							$q->user_for = $user_for->id;
							$questiondata = new QuestionData("", $response);
							$q->value = base64_encode(serialize($questiondata));
							if (!$q->save()){
								addError("Error saving response for question ".$q->id);
							} else {
								//check if we have a preexisting comment
								$c_f = new Comment();
								$comment = $c_f->findOne(array("response_id" => $q->id));
								if ($comment != null && $comments["c_".substr($questionid, 2)] == ""){
									$comment->force_delete();
									FB::info("Forcing delete of comment ".$comment->id);
								} else if ($comment != null){
									$comment->detail = base64_encode($comments["c_".substr($questionid, 2)]);
									if (!$comment->save()){
										addError("Error saving comment id " . $comment->id);
									}
								} else if ($comment == null){
								
									if (isset($comments["c_".substr($questionid, 2)]) && $comments["c_".substr($questionid, 2)] != ""){
										//we have a comment for this question
										$comm = new Comment();
										$comm->response_id = $q->id;
										$comm->detail = base64_encode($comments["c_".substr($questionid, 2)]);
										if (!$comm->save()){
											addError("Error saving comment for question ".$q->id);
										}
									}
								}
								
							}
						}
					}
					
					if (!errorsExist()){
						Manager::add_notice("Survey successfully completed for ".$user_for->name());
					}
		
				} catch (Exception $e){
					addError("That survey does not exist");
					break;
				}

				break;
			
			case "completed_all":
				protect(User::U_TEACHER);
				$surveyinstanceid = (int)$_REQUEST['si_id'];
				$studentid = (int)$_REQUEST['s_id'];
				try {
					$surveyinstance = new SurveyInstance($surveyinstanceid);
					if (!$surveyinstance->isOwner()){
						addError("You do not own that surveyinstance");
						break;
					}
					$result = $surveyinstance->has_student_completed_all($studentid);
					if ($result == SurveyInstance::SI_COMPLETED){
						$completion = "complete";
					} else {
						$completion = "incomplete";
					}
					if ($surveyinstance->isOpen()){
						$active = "open";
					} else {
						$active = "closed";
					}
					$output['link'] = "/ui/images/$active-$completion.png";
					
				} catch (Exception $e){
					addError("Could not access surveyinstance");
					break;
				}
				
				break;
				
			case "create":
				protect(User::U_TEACHER);
				if (!isset($_REQUEST['surveyid'])){
					addError("You must provide a survey id");
					break;
				}
				$surveyid = Manager::require_variable_int("surveyid");
				$courseid = Manager::require_variable_int("courseid");
				$visibility = Manager::require_variable_int("closevisibility");
				$due_m = Manager::require_variable_int("due_m");
				$due_d = Manager::require_variable_int("due_d");
				$due_y = Manager::require_variable_int("due_y");
				$duedate = strtotime("$due_m/$due_d/$due_y 11:59:00 PM");
				
				if (time()-$duedate > 0){
					Manager::add_error("You must specify a date in the future for the closing time of the survey");
				}
				
				
				if (!errorsExist()){
					try {
						$survey = new Survey($surveyid);
						if (!$survey->isOwner()){
							Manager::fatal("you don't own that survey");
							exit();
						}
						$course = new Course($courseid);
						if (!$course->isOwner()){
							Manager::fatal("you don't own that course");
							exit();
						}
						//find all groups within the course
						$pt_s = new ProjectTeam();
						$projectteams = $pt_s->find(array("course_id" => $course->id));
						$ps_s = new ProjectStudent();
						if ($projectteams){
							foreach ($projectteams as $projectteam){
								$surveyinstance = new SurveyInstance();
								$surveyinstance->survey_id = $survey->id;
								$surveyinstance->projectteam_id = $projectteam->id;
								$surveyinstance->date_given = time();
								$surveyinstance->set_closing_date($due_m, $due_d, $due_y);
								$surveyinstance->survey_visibility_after_close = $visibility;
								if (!$surveyinstance->save()){
									Manager::fatal("could not save that surveyinstance");
								} else {
									$projectstudents = $ps_s->find(array("projectteam_id" => $projectteam->id));
									if ($projectstudents){
										foreach ($projectstudents as $projectstudent){
											QuickEvalEmail::SendNewSurveyEmail(new User($projectstudent->user_id), new User(User::current_user_id()), $course);
											
										}
									}
								}
								
								
								
							}
							
						}

					} catch (Exception $e){
						Manager::add_error("couldn't load an object");
					}

				}
				
				
				Manager::add_notice("Successfully sent evaluation. An automatic email reminder will be sent to all students 24 hours before the deadline.");
				header("Location: /viewcourse?course=$_REQUEST[courseid]");
				exit();
				break;
			default:
				addError("Invalid action");
				break;
		}
		break;
	case "surveys":
		switch ($_REQUEST['action']){
			case "clone":
				protect(User::U_TEACHER);
				try {
					$surveyid = Manager::require_variable_int("sid");
					$s = new Survey($surveyid);
					if (!$s->isOwner()){
						addError("You don't own that object");
						break;
					}
					$newsurvey = new Survey();
					$newsurvey->name = "Copy of ".$s->name;
					$newsurvey->description = $s->description;
					if (!$newsurvey->save()){
						addError("Error creating new survey");
						break;
					}
					
					$q_f = new Question();
					$questions = $q_f->find(array("survey_id" => $s->id));
					foreach ($questions as $q){
						$newq = new Question();
						$newq->type = $q->type;
						$newq->weight = $q->weight;
						$newq->data = $q->data;
						$newq->list_order = $q->list_order;
						$newq->survey_id = $newsurvey->id;
						if (!$newq->save()){
							addError("Error duplicating question");							
						}
					}
						
				} catch (Exception $e){
					addError("Error cloning survey");
				}
				
				
				break;
			case "editname":
				protect(User::U_TEACHER);
				try {
					$s = new Survey($_REQUEST['id']);
					if (!$s->isOwner()){
						addError("You do not own that object");
						break;
					}
					
					$s->name = strip_tags($_REQUEST['value']);
					if (!$s->save()){
						addError("There was an error saving the survey name");
						addError($s->get_errors());

					} else {
						$output['value'] = strip_tags($_REQUEST['value']);
					}

				} catch (Exception $e){
					addError("That survey does not exist");
				}
				break;
			case "add":
				protect(User::U_TEACHER);
				$s = new Survey();
				$s->name = $_REQUEST['name'];
				$s->description = $_REQUEST['description'];
				if (!$s->save()){
					addError($s->get_errors());
				} else{
					$output['survey']['name'] = strip_tags($s->name);
					$output['survey']['description'] = strip_tags($s->description);
					$output['survey']['id'] = $s->id;	
				}
				break;
			case "delete":
				protect(User::U_TEACHER);
				try {
					$s = new Survey($_REQUEST['id']);
					if (!$s->isOwner()){
						addError("You do not have permission to delete that evaluation");
						break;
					}
					$q = new Question();
					$questions = $q->find(array("survey_id" => $s->id));
					if ($questions){
						foreach ($questions as $q){
							if (!$q->delete()){
								addError("Error deleting associated question");
							}
						}
					}
					if (!$s->delete()){
						addError("Error deleting evaluation for ".$s->name);
					}
				} catch (Exception $e){
					addError("That survey ID does not exist");
				}

				
				
				break;
			case "list":
				protect(User::U_TEACHER);
				$s = new Survey();
				$surveys = $s->find(array("owner_id" => User::current_user_id()));
				if ($surveys){
					foreach ($surveys as $survey){
						$output['surveys'][] = $survey->get_parameters();
					}
				}
				break;
			
		}
		break;
	case "courses":
		switch ($_REQUEST['action']){
			case "remindUserInvite":
				protect(User::U_TEACHER);
				$courseid = Manager::require_variable_int("c_id");
				$userid = Manager::require_variable_int("u_id");
				try {
					$course = new Course($courseid);
					if (!$course->isOwner()){
						addError("you don't own that course");
						break;
					}
					$user = new User($userid);
					if (!$user){
						addError("Invalid user");
						break;
					}
					if (!$course->student_in_course($user->id)){
						addError("User is not in the course");
						break;
					}
					if ($user->level != User::U_INVITED){
						addError("User is already registered.  you can't resend the invite email");
						break;
					}
					if (!$user->welcome_user(User::get_current_user(), $course)){
						addError("Error re-sending welcome email");
						break;
					}
				} catch (Exception $e){
					addError("Error reminding user");
				}
				break;
			case "list":
				protect(User::U_TEACHER);
				$c = new Course();
				$courses = $c->find(array("owner_id" => User::current_user_id()));
				if ($courses){
					foreach ($courses as $course){
						$output['courses'][] = array_merge($course->get_parameters(), array("students"=>$course->num_students(), "teams"=>$course->num_teams()));
					}
				}
				break;
			case "add":
				protect(User::U_TEACHER);
				$c = new Course();
				$c->name = $_REQUEST['name'];
				if (!$c->save()){
					addError($c->get_errors());
				} else {
					$output['course']['name'] = $c->name;
					$output['course']['id'] = $c->id;
				}
				break;
			case "delete":
				protect(User::U_TEACHER);
				try {
					$c = new Course($_REQUEST['id']);
					if (!$c->isOwner()){
						addError("You do not own that course object. You can't delete it, silly.");
						break;
					}
					$ptf = new ProjectTeam();
					$pts = $ptf->find(array("course_id" => $c->id));
					$psf = new ProjectStudent();
					if ($pts){
						foreach ($pts as $projectteam){
							$tempps = $psf->find(array("projectteam_id" => $projectteam->id));
							if ($tempps){
								foreach ($tempps as $projectstudent){
									if (!$projectstudent->delete()){
										addError("Error deleting project student #".$projectstudent->id);
									}
								}
							}
							if (!$projectteam->delete()){
								addError("Error deleting subsequent project group named ".$projectteam->name);
							}
						}
					}
					if (!$c->delete()){
						addError("There was an error deleting that course");
					}
				} catch (Exception $e){
					addError("That course (#id $_REQUEST[id]) does not exist");
				}

				break;
			case "rename":
				protect(User::U_TEACHER);
				if (!isset($_REQUEST['id'])){
					addError("You must provide an ID");
				} else {
					try {
						$g = new Course($_REQUEST['id']);
						if (!$g->isOwner()){
							addError("You do not own that course!"); 
						} else {
							$g->name = strip_tags($_REQUEST['value']);
							if (!$g->save()){
								addError($g->get_errors());
							} else {
								$output['value'] = strip_tags($_REQUEST['value']);
							}

						}
					} catch (Exception $e){
						addError("Could not find a course with that ID");
					}
				}
				break;

			default:
				addError("Invalid Action");
				break;	
		
		}
		break;
	case "group":
		switch ($_REQUEST['action']){
			case "add":
				protect(User::U_TEACHER);
				$g = new ProjectTeam();
				try {
					$c = new Course($_REQUEST['course_id']);
					if (!$c->isOwner()){
						addError("You don't own that course, so you can't add groups to it");
					} else {
						$g->course_id = $c->id;
					}
				} catch (Exception $e){
					addError("Could not find that course id");
				}
				$g->name = $_REQUEST['name'];
				if (!$g->save()){
					addError($g->get_errors());
				}
				break;
			case "delete":
				protect(User::U_TEACHER);
				if (!isset($_REQUEST['id'])){
					addError("You must provide an ID");
				} else {
					try {
						$g = new ProjectTeam($_REQUEST['id']);
						if (!$g->isOwner()){
							addError("You do not own that Project Team!"); 
						} else {
							$s = new ProjectStudent();
							$students = $s->find(array("projectteam_id" => $g->id));
							if (!$students){
								if (!$g->delete()){
									addError("Error deleting group");
									addError($g->get_errors());
								}
							} else {
								addError("You can not delete a group that still has students in it");
							}
						}
					} catch (Exception $e){
						addError("Could not find a group with that ID");
					}
				}
				
				break;
			case "rename":
				protect(User::U_TEACHER);
				if (!isset($_REQUEST['id'])){
					addError("You must provide an ID");
				} else {
					try {
						$g = new ProjectTeam($_REQUEST['id']);
						if (!$g->isOwner()){
							addError("You do not own that Project Team!"); 
						} else {
							$g->name = strip_tags($_REQUEST['value']);
							if (!$g->save()){
								addError($g->get_errors());
							} else {
								$output['value'] = strip_tags($_REQUEST['value']);
							}

						}
					} catch (Exception $e){
						addError("Could not find a group with that ID");
					}
				}
				
				break;
			default:
				addError("Invalid Action");
				break;	

		}
		break;
	case "viewcourse":
		switch ($_REQUEST['action']){
			case "reinvite_user":
				protect(User::U_TEACHER);
				$uid = (int)($_REQUEST['uid']);
				$cid = (int)($_REQUEST['cid']);
				try {
					$user = new User($uid);
					$course = new Course($cid);
					if (!$course-isOwner()){
						die("you dont own that course");
					}
					if ($user->level == User::U_INVITED){
						$user->welcome_user($_SESSION['current_user'], $course);
					}
					
				} catch (Exception $e){
					
				}
				break;
			case "move":
				protect(User::U_TEACHER);
				$uid = (int)substr($_REQUEST['uid'], 2);
				$tid = (int)substr($_REQUEST['tid'], 2);
				$cid = (int)$_REQUEST['cid'];
				$ps = new ProjectStudent();
				$projectstudent = $ps->find(array("user_id" => $uid));
				$theproj = null;
				if ($projectstudent){
					foreach ($projectstudent as $p){
						try {
							$pt = new ProjectTeam($p->projectteam_id);
							if ($pt->course_id == $cid){
								$theproj = $p;
							}
						} catch (Exception $e){
							addError("Could not find projectteam");
						}
					}
				}
				$theproj->user_id = $uid;
				$theproj->projectteam_id = $tid;

				if (!$theproj->save()){
					addError($theproj->get_errors());
				} else {

				}

				break;
		}
		break;
	case "projectstudent":
		protect(User::U_TEACHER);
		switch ($_REQUEST['action']){
			case "delete":
				$projectstudentid = (int)$_REQUEST['id'];
				try {
					$ps = new ProjectStudent($projectstudentid);
				} catch (Exception $e){
					addError("Invalid Project Student Id");
					break;
				}
				//find parent project team and make sure user owns it
				$pt = new ProjectTeam();
				$projectteam = $pt->findOne(array("id" => $ps->projectteam_id));
				if (!$projectteam){
					addError("Invalid Project Team");
				} else {
					if (!$projectteam->isOwner()){
						addError("You do not own that project student");
					} else {
						if (!$ps->delete()){
							addError("Error deleting project student");
						}
					}
				}
				break;
			default:
				addError("Invalid action");
				break;
			
		}
		break;
	case "students":
		switch ($_REQUEST['action']){
			case "verifylist":
			protect(User::U_TEACHER);
				$list = Text::list_to_users($_REQUEST['names'], $_REQUEST['format']);
				if (!$list){
					$o = "Invalid Student List. Please type to revalidate.";
					break;
				}
				$o = "<table cellpadding=0 cellspacing=0 class='namelist'><thead><tr><th>eMail</th><th>Last Name</th><th>First Name</th></td></thead><tbody>";
				foreach ($list as $l){
					$o .= "<tr>";
						if (Validation::validate_email($l['email'])){
							$o .= "<td class='hgreen'>";
						} else {
							$o .= "<td class='hred'>";
						}
						$o .= "$l[email]</td>";
						if (Validation::validate_string_minimum_length($l['lname'],1)){
							$o .= "<td class='hgreen'>";
						} else {
							$o .= "<td class='hred'>";
						}
						$o .= "$l[lname]</td>";
						if (Validation::validate_string_minimum_length($l['fname'],1)){
							$o .= "<td class='hgreen'>";
						} else {
							$o .= "<td class='hred'>";
						}
						$o .= "$l[fname]</td>";
					$o .= "</tr>";
				}
				$o .= "</tbody></table>";
				$output['nametable'] = $o;
				break;
			case "add":
			protect(User::U_TEACHER);
				/**
				*	System of events
				*
				*	1) find first project group
				*		1.1) if first project group exists, use that, otherwise
				*			1.1.1) create new project group named "Default Group"
				*	2) get list of users ($_REQUEST['names'])
				*		2.1) parse list of users to array
				*		2.2) throw out (and add errors) for incomplete user objects
				*		2.3) iterate through cleaned list of users
				*			2.3.1) if user does not exist, create new user
				*				2.3.1.1) send welcome mail to user
				*			2.3.2) add user to project group from above
				*			2.3.3) notify user of their addition to the class
				*/
				
				try {
					$course = new Course((int)$_REQUEST['course_id']);
				} catch (Exception $e){
					addError("Invalid Course");
					break;
				}
								
				$list = Text::list_to_users(trim($_REQUEST['names']), $_REQUEST['format']);
				if (!$list){
					addError("No students provided, please type in a few");
					break;
				}

				//find projectteam
				$pt_search = new ProjectTeam();
				$pt = $pt_search->findOne(array("owner_id" => User::current_user_id(), "course_id" => (int)$_REQUEST['course_id']));
				if (!$pt){
					$pt = new ProjectTeam();
					$pt->course_id = (int)$_REQUEST['course_id'];
					$pt->name = "Default Group";
					if (!$pt->save()){
						addError("Error creating project team while adding students");
						break;
					}
				}
				

				
				$users = array();
				$userfind = new User();
				foreach ($list as $textuser){
					$t = $userfind->findOne(array("email" => $textuser['email']));
					if (!$t){
						//that user doesn't exist - we need to make and invite one
						$t = new User();
						$t->fname = Sanitize::s_plain($textuser['fname']);
						$t->lname = Sanitize::s_plain($textuser['lname']);
						$t->email = Sanitize::s_plain($textuser['email']);
						$t->set_password("");
						$t->level = User::U_INVITED;
						if (!$t->save()){
							addError($t->get_errors());
							//make sure we dont make a projectstudent for this user
							// we couldn't even make the user
							$t = null;
							continue;
						} else {
							//user was successfully created
							//randomize their auth string
							//send them a welcome email
							$t->randomize_invite_code();
							$t->save();
							if (!$t->welcome_user($_SESSION['current_user'], $course)){
								Manager::add_notice("Error sending email to " . $t->name(). ". Please try adding your students again.");
								$t->delete();
							}
						}
					} else {
						if ($t->id == User::current_user_id()){
							Manager::add_notice("You can not add yourself to the class.");
							continue;
						} else if ($course->student_in_course($t->id)){
							Manager::add_notice($t->name() . " is already enrolled in this course.  They were sent a reminder email.");
							QuickEvalEmail::SendClassAddEmail($t, $_SESSION['current_user'], $course);
							continue;
						}

					}
					
					
					//create projectstudent entry for student
					$ps = new ProjectStudent();
					$ps->projectteam_id = $pt->id;
					$ps->user_id = $t->id;
					if (!$ps->save()){
						addError("There was an error adding the student, ".$t->name().", to the project team");
					}
				}
				
				
				break;
			case "validate":
				$email = strtolower(trim($_REQUEST['email']));
				$key = $_REQUEST['key'];
				$pass1 = $_REQUEST['pass1'];
				$pass2 = $_REQUEST['pass2'];
				$usf = new User();
				$theuser = $usf->findOne(array("email" => $email, "level" => User::U_INVITED));
				if (!$theuser){
					addError("The email, $email, has not been invited to QuickEval yet");
				}
				
				if ($theuser->invite_code != $key){
					addError("Your invite code, $key, is invalid");
				}
				
				if ($pass1 != $pass2){
					addError("Passwords do not match");
				}
				
				if (!User::valid_password($pass1)){
					addError("Password must be at least 6 characters long");
				}
				
				if (!errorsExist()){
					//set password and level
					$theuser->set_password($pass1);
					$theuser->level = User::U_STUDENT;
					if (!$theuser->save()){
						addError("Error activating your account");
					} else {
						User::login($email, $pass1);
						Manager::add_notice("Successfully activated account for ".$theuser->email);
					}
				}

				break;
			default:
				addError("That action does not exist!");
				break;
		}
		break;
	case "profsignup":
		if (!Validation::validate_string_minimum_length($_REQUEST['first'],2)) addError("You must provide a first name");
		if (!Validation::validate_string_minimum_length($_REQUEST['last'],2)) addError("You must provide a last name");
		if (!Validation::validate_email($_REQUEST['email'])) addError("You must provide an email");
		if (!Validation::validate_email($_REQUEST['email2'])) addError("You must provide a confirm email");
		if (!Validation::validate_string_minimum_length($_REQUEST['school'],2)) addError("You must provide a school");
		if (!Validation::validate_string_minimum_length($_REQUEST['phone'],2)) addError("You must provide a phone number");
		if (!Validation::validate_string_minimum_length($_REQUEST['type'],2)) addError("You must provide a type");
		if ($_REQUEST['email'] != $_REQUEST['email2']) addError("Emails must match!");
		
		if (!errorsExist()){
			//Send the professor email
			$infoarr = array("first" => $_REQUEST['first'], "last" => $_REQUEST['last'], "email" => $_REQUEST['email'], "level" => User::U_TEACHER);
			$dataArr = base64_encode(serialize($infoarr));
			$mail = new PHPMailer();
			$mail->From = Sanitize::s_plain($_REQUEST['email']);
			$mail->FromName = Sanitize::s_plain($_REQUEST['first'] . " " . $_REQUEST['last']);
			$mail->AddAddress(ADMINISTRATOR_EMAIL);
			$mail->Subject = "QuickEval Professor Signup Information";
			$body =  "A professor wishes to signup for quickeval!\r\n\r\n";
			$body .= "Name: $_REQUEST[first] $_REQUEST[last]\r\n";
			$body .= "Email: $_REQUEST[email]\r\n";
			$body .= "School: $_REQUEST[school]\r\n";
			$body .= "Phone: $_REQUEST[phone]\r\n";
			$body .= "Type: $_REQUEST[type]\r\n";
			$body .= "Accept Link: ". WEB_ROOT . "profsignup_confirm?key=$dataArr\r\n";
			$body .= "\r\nThanks\r\n-The QuickEval Robot";
			$mail->Body = $body;
			if (!$mail->Send()){
				addError("There was an error sending your email");
			} else {
				QuickEvalEmail::SendProfSignupInitiateEmail(Sanitize::s_plain($_REQUEST['email']));
			}
		}
		
		break;
	case "profsignup_confirm":
		protect(User::U_ADMIN);
		if (!Validation::validate_string_minimum_length($_REQUEST['first'],2)) addError("You must provide a first name");
		if (!Validation::validate_string_minimum_length($_REQUEST['last'],2)) addError("You must provide a last name");
		if (!Validation::validate_email($_REQUEST['email'])) addError("You must provide an email");
		if ($_REQUEST['password'] == ""){
			//generate password
			$password = Text::random_string(8, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890");
		} else {
			$password = $_REQUEST['password'];
		}
		$u = new User();
		$u->fname = $_REQUEST['first'];
		$u->lname = $_REQUEST['last'];
		$u->level = $_REQUEST['level'];
		$u->email = $_REQUEST['email'];
		$u->set_password($password);
		
		if (!$u->save()){
			addError($u->get_errors());
		} else {
			//new user success.  Send them an email.
			QuickEvalEmail::SendNewManualAddEmail($u, $password);
		}
		break;
	case "contact":
		if (!Validation::validate_string_minimum_length($_REQUEST['name'], 2)){
			addError("Name must be at least 2 characters long");
		}
		if (!Validation::validate_email($_REQUEST['email'])){
			addError("Email must be valid");
		}
		if (!Validation::validate_string_minimum_length($_REQUEST['message'], 2)){
			addError("Message must be at least 2 characters long");
		}
		if (!errorsExist()){
			$mail = new PHPMailer();
			$mail->From = Sanitize::s_plain($_REQUEST['email']);
			$mail->FromName = Sanitize::s_plain($_REQUEST['name']);
			$mail->AddAddress("needhelpcallari@gmail.com");
			$mail->Subject = "QuickEval Contact Form Submission";
			$mail->Body = "Name: $_REQUEST[name]\r\nMessage: $_REQUEST[message]\r\n";
			if (!$mail->Send()){
				addError("There was an error sending your email");
			}
		} 
		
		break;
	case "questions":
		switch ($_REQUEST['action']){
			case "add":
				protect(User::U_TEACHER);
				$q = new Question();
				$qd = null;
				if (!Validation::validate_string_minimum_length($_REQUEST['name'], 2)){
					addError("Question must be at least 2 characters long");
				}
				$choices = array();
				if (isset($_REQUEST['choices'])){
					foreach ($_REQUEST['choices'] as $choice){
						if ($choice != "") $choices[] = Sanitize::s_plain($choice);
					}
				}
				
				$q->type = $_REQUEST['type'];
				if (($_REQUEST['type'] == Question::Q_MULTIPLECHOICE || $_REQUEST['type'] == Question::Q_RATING || $_REQUEST['type'] == Question::Q_CHECKBOXES) && count($choices) < 2){
					addError("You must have at least two options for the question type you specified");
				}
				$qd = new QuestionData(Sanitize::s_plain($_REQUEST['name']), $choices);

				try {
					$s = new Survey($_REQUEST['survey_id']);
					if (!$s->isOwner()){
						addError("You don't own that survey, silly");
					} else {
						$q->survey_id = $s->id;
					}
				} catch (Exception $e){
					addError("That survey doesn't exist");
				}
				$q->data = base64_encode(serialize($qd));
				if (!errorsExist()){
					if (!$q->save()){
						addError($q->get_errors());
					}
				}
				break;
			case "delete":
				protect(User::U_TEACHER);
				try {
					$q = new Question((int)$_REQUEST['id']);
					if (!$q->isOwner()){
						addError("You don't have rights to delete that question");
						break;
					}
					if (!$q->delete()){
						addError("There was an error deleting that question");
					}
				} catch (Exception $e){
					addError("That question id doesn't exist");
				}
				break;
			case "sort":
				protect(User::U_TEACHER);
				$questions = array();
				$count = 1;
				foreach ($_REQUEST['questiontable'] as $q){
					$questions[$count++] = (int)substr($q, 2);
				}
				foreach ($questions as $order=>$questionnum){
					try {
						$q = new Question($questionnum);
						if (!$q->isOwner()){
							addError("You don't have rights to edit question $questionnum");
						}
						//save time on stuff we dont need to update
						if ($q->list_order != $order){
							$q->list_order = $order;
							if (!$q->save()){
								addError("Could not save the order on question #$questionnum");
							}
						}
					} catch (Exception $e){
						addError("Question #$questionnum doesn't exist");
					}
				}
				
				
				
				break;
			default:
				addError("Invalid Action");
				break;
		}
		break;
	case "forgotpassword":
		//get user info first
		//validate email, if not, shoot back an error
		//otherwise, make the valid user object
		
		$email = strtolower(Manager::require_variable("email"));
		if (!Validation::validate_email($email)){
			addError("That email address is invalid");
			break;
		}
		$u_f = new User();
		$requesteduser = $u_f->findOne(array("email" => $email));
		if (!$requesteduser){
			//invalid user
			addError("That email address is invalid");
			break;
		}

		switch ($_REQUEST['action']){
			case "initiate":
				//valid user. regenerate auth key and email user about pending reset
				
				if ($requesteduser->level == User::U_INVITED){
					addError("That email has already been invited to Quickeval.  Please find the activation email that was sent to you first to activate your account.");
					
				} else {
				
					$requesteduser->randomize_invite_code();
					if (!$requesteduser->save()){
						addError("Error generating authorization token");
					} else {
						//shoot them an email about pending authorization
						QuickEvalEmail::SendNewResetPassword($requesteduser);
					}
				}
				break;
			case "change":
				if (!isset($_REQUEST['email']) || !isset($_REQUEST['pass1']) || !isset($_REQUEST['pass2']) || !isset($_REQUEST['key'])){
					addError("All fields are required");
					break;
				}
				if ($_REQUEST['key'] != $requesteduser->invite_code){
					addError("Invalid authorization token.  Try resetting password again.");
					break;
				}
				if (!User::valid_password($_REQUEST['pass1'])){
					addError("Password must be at least 6 characters long");
					break;
				}
				if ($_REQUEST['pass1'] != $_REQUEST['pass2']){
					addError("Both passwords must be the same");
					break;
				}			
				$requesteduser->set_password($_REQUEST['pass2']);
				if (!$requesteduser->save()){
					addError("Error resetting password");
					break;
				}
		}
		
		break;
	case "mysettings":
		protect(User::U_STUDENT);
		if ($_REQUEST['oldpassword'] != "" && $_REQUEST['newpassword'] != "" && $_REQUEST['newpasswordverify'] != ""){
		//check to see if we can change password
		
			$valid = 1;
			if (!$_SESSION['current_user']->check_password($_REQUEST['oldpassword'])){
				addError("Old password does not match");
				$valid = 0;
			}
			if ($_REQUEST['newpassword'] != $_REQUEST['newpasswordverify']){
				addError("New Passwords must match!");
				$valid = 0;
			}
			if (!User::valid_password($_REQUEST['newpassword'])){
				addError("Password must be at least 6 characters long");
				$valid = 0;
			}
			if ($valid){
				$_SESSION['current_user']->set_password($_REQUEST['newpassword']);
				$_SESSION['current_user']->save();
			}
		}
	
		$newobj = clone $_SESSION['current_user'];
		$newobj->fname = Sanitize::s_plain($_REQUEST['fname']);
		$newobj->lname = Sanitize::s_plain($_REQUEST['lname']);
	
		if ($_REQUEST['fname'] != $_SESSION['current_user']->fname || $_REQUEST['lname'] != $_SESSION['current_user']->lname){
			if ($newobj->validate()){
				$newobj->save();
				$_SESSION['current_user'] = $newobj;
			} else {
				addError($newobj->get_errors());
			}
		}

		break;
	case "search":
		protect(User::U_TEACHER);
		$db = DBClass::start();
		$numresults = 0;
		$totalresults = 0;
		$query = $db->s($_REQUEST['query']);
		$output['html'] = "<p id='searchresults'>";
		$results = array();
		
		//Courses
		$c = new Course();
		$sql = "SELECT * FROM `Courses` WHERE active = 1 AND owner_id = ".$db->s(User::current_user_id())." AND (`name` LIKE \"%".$query."%\" OR `name` LIKE \"".$query."%\")  ORDER BY `name`";
		$courses = $c->findSQL($sql);
		$o = "";
		if ($courses && count($courses) > 0){
			foreach ($courses as $course){
				$o .= searchResult("/courses", "/ui/images/course.png", hilightSearch($_REQUEST['query'], $course->name), "Course");
				$numresults++;
			}
		}
		$totalresults += $numresults;
		$output['html'] .= "<span onclick='toggleSearch(\"search_category\");' class='category searchOpen'>Courses ($numresults Result".Text::plural($numresults).")</span><span id='search_category'>$o</span>";

		$numresults = 0;
		//Evaluations
		$e = new Survey();
		$sql = "SELECT * FROM `Surveys` WHERE active = 1 AND owner_id = ".$db->s(User::current_user_id())." AND (`name` LIKE \"%".$query."%\" OR `name` LIKE \"".$query."%\" OR `description` LIKE \"%".$query."%\" OR `description` LIKE \"".$query."%\")  ORDER BY `name`";
		$evaluations = $e->findSQL($sql);
		$o = "";
		if ($evaluations && count($evaluations) > 0){
			foreach ($evaluations as $eval){
				$o .= searchResult("/questions?survey=".$eval->id, "/ui/images/evaluation.png", hilightSearch($_REQUEST['query'], $eval->name), hilightSearch($_REQUEST['query'], $eval->description));
				$numresults++;
			}
		}
		$output['html'] .= "<span onclick='toggleSearch(\"search_eval\");' class='category searchOpen'>Evaluations ($numresults Result".Text::plural($numresults).")</span><span id='search_eval'>$o</span>";

		$totalresults += $numresults;
		

		$output['html'] .= '<span class="seperator"><a href="/page.php?controller=faq">Frequently Asked Questions</a></span><br class="break" />';
		$output['html'] .= "</p>";
		echo $output['html'];
		exit();
		break;
	default:
		addError("Invalid Controller Name");
		break;
}



if (isset($output['errors']) && count($output['errors']) > 0){
	//not successful
	$output['success'] = false;
} else {
	$output['success'] = true;
}

echo json_encode($output);

?>