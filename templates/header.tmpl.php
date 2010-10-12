<div id="header">
	<div class="container_12">
		<div class="grid_4"><div id="logo"><a href="/" id="logo_link">&nbsp;</a></div>&nbsp;</div>
		<div class="grid_4">&nbsp;</div>
		<div class="grid_4">
			
				<?php 
				if (isset($_SESSION['current_user']) && $_SESSION['logged_in'] == true) {
					Partial::Render_Partial("loggedin");
				} else {
					Partial::Render_Partial("login");
				} ?>
			</div>
		</div>
	</div>
</div>
<?php
function linkme($name, $cur, $address, $text){
	$link = "<li><a ";
	$activestr = "class='active' ";
	if ($name == $cur){
		$link .= $activestr;
	}
	$link .= "href='$address'>$text</a></li>";
	return $link;
}
?>
<div id="nav">
	<ul>
		<?php if (User::can_access(User::U_STUDENT)){ ?>
			<?php if (User::can_access(User::U_TEACHER)){ ?>
				<?php echo linkme("results", $page, "/results", "Overview"); ?>
				<?php echo linkme("courses", $page, "/courses", "Courses"); ?>
			<?php } ?>
			<?php echo linkme("evaluations", $page, "/evaluations", "Evaluations"); ?>
			<?php echo linkme("mysettings", $page, "/mysettings", "My Settings"); ?>

		<?php } else { //we aren't logged in'?>
			<?php echo linkme("about", $page, "/about", "About Us"); ?>
			<?php echo linkme("contact", $page, "/contact", "Contact"); ?>
			<?php echo linkme("privacy", $page, "/privacy", "Privacy Policy"); ?>
			<?php echo linkme("termsofuse", $page, "/termsofuse", "Terms of Use"); ?>
			
		<?php } ?>
	</ul>
</div>