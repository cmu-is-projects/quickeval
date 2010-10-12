<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<!-- Info -->
	<title>QuickEval - <?php echo $title; ?></title>
<?php echo Partial::Render_Partial("metatags");?>
<!-- JavaScript -->
	<!-- JavaScript -->
	<?php Partial::Render_Partial("jstags", 
	array("scripts" => array(
		"jquery-1.3.2.min", 
		"jquery-ui-1.7.2.custom.min",
		"sevenup.0.2.min",
		"custom",
		"facebox",
		))); ?>
<script type="text/javascript">
$(document).ready(function(){
	$("#demovideo").hide();   
	$('a[rel*=facebox]').facebox();
});
</script>
<link rel="stylesheet" href="/ui/css/errorboxes.css" type="text/css" />
<link rel="stylesheet" href="/ui/css/homestyle.css" type="text/css" />
<link rel="stylesheet" href="/ui/css/facebox.css" type="text/css" media="screen" />
</head>
<body id="login">
	<div id="wrappertop"></div>
	<div id="wrapper">
		<div id="content">
			<div id="header">
				<h1><a href="/home"><img src="/images/qe_logo.gif" alt="QuickEval"></a></h1>
			</div>
			<div id="darkbanner" class="banner320">
				<h2><?php echo $title; ?></h2>
			</div>
			<div id="darkbannerwrap"></div>
			<?php echo $content; ?>	
		</div>
	</div>	
	<div id="wrapperbottom_branding">
		<div id="wrapperbottom_branding_text">
			
			<p>Professor? <a href="/profsignup" class="profsign">Sign up here!</a> or <a  href="#demovideo" class="profsign" rel="facebox" title="Demo Video">Watch the demo!</a></p><br />
			<p>
				<a href="/about">About Us</a>&nbsp;&nbsp;|&nbsp;&nbsp;
				<a href="/contact">Contact</a>&nbsp;&nbsp;|&nbsp;&nbsp;
				<a href="/privacy">Privacy Policy</a>&nbsp;&nbsp;|&nbsp;&nbsp;
				<a href="/termsofuse">Terms of Use</a>
			</p>
			<p>By QuickEval. Simple Peer Evaluations</p>
		</div>
		<div id="loginfooter">
			<div id="loginlinks">
				<a href="http://is.hss.cmu.edu" title="Information Systems"><div class="infosys">&nbsp;</div></a>
				<a href="http://www.twitter.com/quickeval" title="Twitter"><div class="twitter">&nbsp;</div></a>
				<a href="http://www.facebook.com/home.php?#/pages/QuickEval/178231018575?ref=search&sid=4804495.4289399316..1" title="Facebook"><div class="facebook">&nbsp;</div></a>
			</div>
		</div>
	</div>
	
	<div id="demovideo">
	<object width="621" height="450"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=4355711&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=0&amp;show_portrait=0&amp;color=00ADEF&amp;fullscreen=1" /><embed src="http://vimeo.com/moogaloop.swf?clip_id=4355711&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=0&amp;show_portrait=0&amp;color=00ADEF&amp;fullscreen=1" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="621" height="450"></embed></object></div>
	<?php Partial::Render_Partial("uservoice", array("hovercolor" => "#333F5F", "backgroundcolor" => "#000"));?>
</body>
</html>