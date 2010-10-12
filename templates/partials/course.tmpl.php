<script type="text/javascript">
$(document).ready(function(){
	$("#quickform").hide();	
	$("#newgroup").hide();
	$("#sendeval").hide();	
	$('a[rel*=facebox]').facebox();
	
	var tallest = 0;
	$(".groupbox").each(function(){
		if ($(this).height() > tallest){
			tallest = $(this).height();
		}
	});
	$(".groupbox").each(function(){
		//$(this).height(tallest);
	});
	
	$("#newgroup").submit(function(event){
		$.ajax({
			data: $("#newgroup").serialize(),
			success: function(data, textStatus){
				if (data['success']){
					window.location.reload();
				} else {
					addErrorBox($("#newgroup"), "Error adding group", data.errors);
				}
			}
		});
		event.preventDefault();

	});
	$(".studentrow").draggable({helper:'original', revert:'invalid', handle: '.draghandle'});
	$(".groupbox").each(function(){
		$(this).droppable({
			accept: ".studentrow",
			activeClass: 'droppable-active',
			hoverClass: 'droppable-hover',
			drop: function(ev, ui){
				$(ui.draggable).css("padding", "0");
				$(ui.draggable).css("left", "0");
				$(ui.draggable).css("top", "0");
				$(this).append($(ui.draggable));
				$.ajax({
					data: "controller=viewcourse&action=move&cid=<?php echo $courseid;?>&uid="+$(ui.draggable).attr("id")+"&tid="+$(this).attr("id"),
					success: function(data, textStatus){
						if (data['success']){
							$(ui.draggable).show("highlight", 2000);
						} else {
							addErrorBox($("#studentform"), "Error moving student. Please Refresh", data.errors);
						}
					}
				});
				$(this).find(".droptarget").addClass("hidden");

			},
			over: function(ev, ui){
				$(this).find(".droptarget").toggleClass("hidden");
			},
			out: function(ev, ui){
				$(this).find(".droptarget").toggleClass("hidden");
			}
		});
	});

});
function isValidEmailAddress(emailAddress) {
var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
return pattern.test(emailAddress);
}
function parseStudentDataToTable(data, type){
	var o = "<table cellpadding=0 cellspacing=0 class='namelist'><thead><tr><th>eMail</th><th>Last Name</th><th>First Name</th></td></thead><tbody>";
	var lines = data.split("\n");
	for (var x = 0; x < lines.length; x++){
		var linedata = lines[x].split(",");
		var email = "";
		var fname = "";
		var lname = "";
		if (type == 1){
			//csv = email,lastname,firstname
			email = linedata[0];
			lname = linedata[1];
			fname = linedata[2];
		} else if (type == 2){
			//andrew = junk,andrewwithoutandrew,lastname,firstname
			email = linedata[1]+"@andrew.cmu.edu";
			lname = linedata[2];
			fname = linedata[3];
		} else {
			//invalid type
			return "";
		}
		o += "<tr>";
		if (isValidEmailAddress(email)){
			o += "<td class='hgreen'>"+email + "</td>";
		} else {
			o += "<td class='hred'>"+email + "</td>";
		}
		if (lname){
			o += "<td class='hgreen'>"+lname + "</td>";
		} else {
			o += "<td class='hred'></td>";
		}
		if (fname){
			o += "<td class='hgreen'>"+fname + "</td>";
		} else {
			o += "<td class='hred'></td>";
		}
		o += "</tr>";
		
		
		
	}
	
	o += "</tbody></table>";
	return o;
}
$(function() {
		
	$("#showadd").click(function() {
		$("#showadd").toggle();
		$("#quickform").toggle('blind');
	});	
	$("#canceladd").click(function() {
		$("#showadd").toggle();
		$("#quickform").toggle('blind');
	});

	$("#addgroup").click(function() {
		$("#addgroup").toggle();
		$("#newgroup").toggle('blind');
	});	
	$("#cancelAddCourse").click(function() {
		$("#addgroup").toggle();
		$("#newgroup").toggle('blind');
	});
	$("#studentform").submit(function(event){
		$.ajax({
			data: "controller=students&action=add&"+$("#studentform").serialize(),
			success: function(data, textStatus){
				if (data['success']){
					window.location.reload();
				} else {
					addErrorBox($("#studentform"), "Error verifying student list", data.errors);
				}
			}
		});
		event.preventDefault();
	})
	$("#studentlist").keyup(function(){
		$("#studentresult").html(parseStudentDataToTable($("#studentlist").attr("value"), $("input[name='format']:checked").val()));
	});
});

function remindUserEmail(user_id, course_id){
	if (confirm("Do you want to send a reminder email to this student to activate their account?")){
		$.ajax({
			data: "controller=courses&action=remindUserInvite&u_id="+user_id+"&c_id="+course_id,
			success: function(data, textStatus){
				if (data['success']){
					alert("User has been sent another welcome email");
				} else {
					addErrorBox($("#studentform"), "Could not send reminder email", data.errors);
				}
			}
		});
	} else {
		alert("Email not sent");
	}
	
}

</script>

<form id="studentform" class="wufoo ">
	<input type='hidden' name='course_id' value='<?php echo $courseid;?>' />
	<ul id="quickform">
		<li id="fo27li1" class=" ">

		<li id="q_465_list" class="">
			<label class="desc" id="q_465_label" for="q_465">What format is the data in?<span class="req">*</span></label>
			<input id="q_465_1" name="format" class="field radio" value="1" type="radio" checked>
			<label class="choice" for="q_465_1" checked="true">CSV (email,lastname,firstname)</label>
			<input id="q_465_2" name="format" class="field radio" value="2" type="radio">
			<label class="choice" for="q_465_2">Andrew Course Data (S97,mouse,Mouse,Mickey,SIA,IM....)</label>
		<p class="instruct" id="instruct1">CSV = Comma Separated Values<br /><br />Andrew Course Data is for CMU Professors only. Check out the FAQ page for instructions about how to get this file.</p>		
		</li>
		<li id="fo27li1" class=" ">
			<label class="desc" id="title2" for="name">Student Email(s) and Names(s)<span id="req_1" class="req">*</span></label>
			<div>
				<textarea id="studentlist" class="field textarea medium" name="names" rows="10" cols="10"></textarea> 
			</div>	
			<p class="instruct " id="instruct"><small>Type each student's information on a separate line or paste the appropriate data file into the textbox</small></p>
		</li>
		<div id="studentresult"></div>
				
		<li>
			<input id="surveysubmit" type="submit" value="Save" tabindex="3" /> or 
			<a href="#" id="canceladd"><small>Cancel</small></a>
		</li>
	</ul>	
</form>
<form id="newgroup" class="wufoo ">
	<input type='hidden' name='controller' value='group' />
	<input type='hidden' name='action' value='add' />
	<input type='hidden' name='course_id' value='<?php echo $courseid;?>' />
	
	<ul id="quickform">
		<li id="fo27li1" class=" ">
		<label class="desc" id="title2" for="name">Group Name<span id="req_1" class="req">*</span></label>
		<div>
			<input id="groupname" class="field text medium" name="name" />
			</div>	
			<p class="instruct " id="instruct"><small>Name of the group</small></p>
		</li>
		<li>
			<input id="addcoursebutton" type="submit" value="Add Group" tabindex="3" /> or 
			<a href="#" id="cancelAddCourse"><small>Cancel</small></a>
		</li>

	</ul>

</form>

<div class="clear">&nbsp;</div>

<?php
foreach ($groups as $groupid=>$students){
	try {
		$group = new ProjectTeam($groupid);

	} catch (Exception $w){
		exit();
	}

?>
<div id="g_<?php echo $groupid; ?>" class="grid_8 groupbox">
	<script type="text/javascript">
	document.write(deleteButton("Delete this group?", "group", <?php echo $groupid; ?>, "g", false));
	</script>
	<h3><?php Partial::Render_Partial("liveedit", array("controller" => "group", "action" => "rename", "value" => $group->name, "id" => $groupid)); ?></h3>
	<?php
	foreach ($students as $projectstudent){
		try {
			$s = new User($projectstudent->user_id);
			Partial::Render_Partial("studentrow", array("s" => $s, "psid" => $projectstudent->id));
		} catch (Exception $e){
		 continue;
		}
	}
	?>
	<div style="clear:both; border:1px dotted black;" class="hidden droptarget">Drop Here</div>
</div>
<?php
}
?>

<div style="clear:both;">
	<?php Partial::Render_Partial("iconbutton", array("id" => "sendevaluation", "text" => "Send Evaluation", "icon" => "email", "linked" => "#sendeval", "rel" =>"facebox")); ?>
	<?php Partial::Render_Partial("iconbutton", array("id" => "viewresults", "text" => "View Results", "icon" => "pie_chart", "linked" => "/results?course=".$_GET['course'])); ?>
</div>


<div id="sendeval">
<?php Partial::Render_Partial("forms/sendevaluation", array("curcourseid" => $courseid)); ?>
</div>