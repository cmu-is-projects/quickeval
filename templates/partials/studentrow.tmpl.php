<ul id="s_<?php echo $s->id; ?>" class="studentrow"><li><img src='/images/drag.png' alt='drag' class='draghandle' /><?php echo $s->lname . ", " . $s->fname; ?></li>
<li><img src="ui/images/icons/spacer.gif" /><a href="mailto:<?php echo $s->email; ?>"><?php echo $s->email; ?></a></li>
<li style="width: 400px"><?php
if ($s->level == User::U_INVITED){
	echo '<span class="red">Not Registered</span> <a href="javascript:remindUserEmail('.$s->id.','.$_GET['course'].');"><img src="ui/images/icons/bulb.png" alt="Send Reminder" title="Send Reminder" /></a>';
} else {
	echo '<span class="green">Registered</span>&nbsp;';
}
?>
&nbsp;&nbsp;<img id="delete_<?php echo $psid; ?>" class="point" src="/ui/images/icons/delete.png" name="delete" alt="Delete this student from this course?" onclick='javascript:deleteItem("projectstudent",<?php echo $psid; ?>, "ps", false);'>
</li>
</ul>