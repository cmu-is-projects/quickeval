//Configuration for AJAX
$.ajaxSetup({
  url: "/data.php",
  type: "POST",
  dataType: "json"
});

//Bind the loadingindicator to ajaxloading
$(document).ready(function(){
	$("#ajaxloadingindicator").bind("ajaxSend", function(){
		$(this).show();
	}).bind("ajaxComplete", function(){
		$(this).hide();
	});
});


function addInformationBox(objToAppendTo, title, arrayoferrors, boxtype){
	var d = new Date();
	var theName = "noticebox"+boxtype+d.getTime();
	var o = "<div id='"+theName+"' class='"+boxtype+"'><a href='#' onclick='$(\"#"+theName+"\").hide(\"blind\");'>Hide</a><h3>"+title+"</h3><p><ul>";
	if (arrayoferrors != null){
		for (var i = 0; i < arrayoferrors.length; i++){
			var xx = document.createElement("li");
			o += "<li>"+arrayoferrors[i]+"</li>";
		}
	}
	o += "</ul></p></div>";
	objToAppendTo.prepend(o);
	
	$("#"+theName).show("slide", {direction: "down"});
	setTimeout("$('#"+theName+"').hide('blind', {direction:'vertical'})", 6000);
}


function addErrorBox(objToAppendTo, title, arrayoferrors){
	addInformationBox(objToAppendTo, title, arrayoferrors, "errorbar");
}
function addNoticeBox(objToAppendTo, title, arrayoferrors){
	addInformationBox(objToAppendTo, title, arrayoferrors, "noticebar");
}

function deleteItem(controller, id, idname, animate){
	if (confirm("Are you sure you want to delete that item?")){
	var info = "controller="+controller+"&action=delete&id="+id;
		$.ajax({
			data: info,
			success: function(data, textStatus){
				if (data['success']){
					if (animate == null)
						$("#"+idname+"_"+id).hide("blind");
					else
						window.location.reload();
				} else {
					addErrorBox($("#"+idname+"_"+id), "Error deleting " + controller, data.errors);
				}
			}
		});	
	}
}
function curtime(){
	var d = new Date();
	return d.getTime();
}

function deleteButton(text, controller, id, idname, animate){
	return "<img id='delete_"+id+"' class='right point' src='/ui/images/icons/delete.png' name='delete' alt='"+text+"' onclick='javascript:deleteItem(\""+controller+"\","+id+",\""+idname+"\", "+animate+");' />";
}

var last_id = null;

//////////////////////////////////
//AJAX Creation
//////////////////////////////////

function new_course_row(objToAddTo, data){
	last_id = data.id;
	objToAddTo.append("<div class='datarow' id='courses_"+data.id+"'>"+deleteButton("Delete Course", "courses", data.id, "courses")+"<a href='/viewcourse?course="+data.id+"'>"+data.name+"</a>"+"<span class='coursedescript'>"  +"<br />Project Groups: " + data.teams+ "<br />Total Students: " + data.students+"</div>"+"</div>");
	$("#courses_"+data.id).hide();
	$("#courses_"+data.id).show("highlight", 2000);
}
function cloneSurvey(surveyId){
	var info = "controller=surveys&action=clone&sid="+surveyId;
		$.ajax({
			data: info,
			success: function(data, textStatus){
				if (data['success']){
					window.location.reload();
				} else {
					alert("Error cloning survey");
				}
			}
		});	
}

function new_survey_row(objToAddTo, data){
	last_id = data.id;
	objToAddTo.append("<div class='datarow' id='surveys_"+data.id+"'>"+deleteButton("Delete Evaluation", "surveys",data.id,"surveys")+"<span class='clonesurvey'><a href='#' class='clonelink' title='clone survey' onClick='cloneSurvey("+data.id+");'><img src='ui/images/pageclone.png' alt='clone img' /> </a></span><a href='/questions?survey="+data.id+"'>" + data.name + "</a><br />" + "<span class='coursedescript'>" + data.description +"</span></div>");
	$("#surveys_"+data.id).hide();
	$("#surveys_"+data.id).show("highlight", 2000);
}