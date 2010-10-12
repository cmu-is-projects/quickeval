<script type="text/javascript">
$(document).ready(function(){
	$(".classlist .classarea:gt(0)").hide();	
	$(".classhead").click(function() {
		$(this).next(".classarea").slideToggle(500);
		$(this).toggleClass("open");
		return false;
	});
});
</script>

<ol class="classlist">
	<!-- CLASS ONE -->

	<!-- CLASS TWO -->
	<li>
		<div class="classhead point">05-310 Spring 2009: Group &#35;1</div>
		<div class="classarea">
			<ul class="evalrow evalhead">
				<li>Evaluation Name</li> 
				<li class="postdate">Date Posted</li>
				<li>Date Due</li>
				<li class="student">Jared</li>
				<li class="student">Mary</li>
				<li class="student">Jared</li>
				<li class="student">Mary</li>
				<li>&nbsp;</li>
			</ul>
			<ul class="evalrow">
				<li><a href="#">Evaluation #1</a></li> 
				<li class="postdate">Jan 18</li>
				<li>Jan 15</li>
				<li class="student"><a href="#"><img class="icon" src="ui/images/closed-complete.png" /></a></li>
				<li class="student"><a href="#"><img class="icon" src="ui/images/closed-complete.png" /></a></li>
				<li class="student"><a href="#"><img class="icon" src="ui/images/closed-complete.png" /></a></li>
				<li class="student"><a href="#"><img class="icon" src="ui/images/closed-complete.png" /></a></li>
				<li><a href="#"><img src="ui/images/viewresults.png" /> view results</a></li>
			</ul>
		</div>
	</li>
	<!-- CLASS THREE -->
	<li>
		<div class="classhead point">15-111 Fall 2008: Group &#35;6</div>
		<div class="classarea">
			<ul class="evalrow evalhead">
				<li>Evaluation Name</li> 
				<li class="postdate">Date Posted</li>
				<li>Date Due</li>
				<li class="student">Jared</li>
				<li class="student">Mary</li>
				<li class="student">Jared</li>
				<li class="student">Mary</li>
				<li>&nbsp;</li>
			</ul>
			<ul class="evalrow">
				<li><a href="#">Evaluation #1</a></li> 
				<li class="postdate">Jan 18</li>
				<li>Jan 15</li>
				<li class="student"><a href="#"><img class="icon" src="ui/images/closed-complete.png" /></a></li>
				<li class="student"><a href="#"><img class="icon" src="ui/images/closed-complete.png" /></a></li>
				<li class="student"><a href="#"><img class="icon" src="ui/images/closed-complete.png" /></a></li>
				<li class="student"><a href="#"><img class="icon" src="ui/images/closed-complete.png" /></a></li>
				<li><a href="#"><img src="ui/images/viewresults.png" /> view results</a></li>
			</ul>
			<ul class="evalrow current">
				<li><a href="#">Evaluation #2</a></li> 
				<li class="postdate">Jan 18</li>
				<li>Jan 15</li>
				<li class="student"><a href="#"><img class="icon" src="ui/images/open-complete.png" /></a></li>
				<li class="student"><a href="#"><img class="icon" src="ui/images/open-complete.png" /></a></li>
				<li class="student"><a href="#"><img class="icon" src="ui/images/open-incomplete.png" /></a></li>
				<li class="student"><a href="#"><img class="icon" src="ui/images/open-incomplete.png" /></a></li>
				<li><a href="#"><img src="ui/images/viewresults.png" /> view results</a></li>
			</ul>
		</div>
	</li>	
</ol>