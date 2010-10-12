<?php require_once("User.php"); ?>
<div class="medtext">QuickEval allows you to</div>
<div class="bigtext">easily evaluate your peers</div>
<div class="clear">&nbsp;></div>
<a href="#demovideo" rel="facebox"><img src="images/playdemo.jpg" alt="Click to play demo" title="Click to play demo" class="left" /></a>
       
<div id="demovideo">
<object width="621" height="450"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=4355711&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=0&amp;show_portrait=0&amp;color=00ADEF&amp;fullscreen=1" /><embed src="http://vimeo.com/moogaloop.swf?clip_id=4355711&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=0&amp;show_portrait=0&amp;color=00ADEF&amp;fullscreen=1" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="621" height="450"></embed></object></div>


<?php if (User::can_access(User::U_TEACHER)) {  ?>
	<ul class="featurelist">You can&hellip;
		<li>&raquo; Create Custom <a href="/evaluations">Evaluations</a></li>
		<li>&raquo; Create <a href="/courses">Courses with Project Groups</a></li>
		<li>&raquo; Analyze <a href="/results">Performance Trends</a></li>
	</ul>  
<?php } else { ?>
	<ul class="featurelist">You can&hellip;
		<li>&raquo; Fill out Peer <a href="/evaluations">Evaluations</a></li>
		<li>&raquo; View Evaluation Results</li>
		<li>&raquo; View Performance Trends Over Time</li>
	</ul>	
<?php } ?>
