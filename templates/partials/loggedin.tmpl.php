<?php require_once("User.php"); ?>
<div id="login" class="small">
<span class="loggedin"><p>Welcome, <strong class="white"><?php echo $_SESSION['current_user']->name(); ?></strong> <a href="/logout">[Log Out]</a></p></span>
<?php if (User::can_access(User::U_TEACHER)) { ?><form id="searchform">
	<div><input type='text' name='search' id="searchquerystring" onkeyup="lookup();" value="search..." autocomplete="off" class='presearch' /></div>
	<div id="suggestions"></div>
</form><?php } ?>
