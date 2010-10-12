<h2>An error has occurred:</h2>
<form>
	<fieldset>
	<p><b>Controller:</b> <?php echo (isset($controller) ? $controller : "None"); ?></p>
	<p><b>Action:</b> <?php echo (isset($action) ? $action : "None"); ?></p>
	<p><b>Message:</b> <?php echo (isset($message) ? $message : "None"); ?></p>
	<br />
	<p>Please contact QuickEval support via the contact link here: <a href="/contact"><?php echo WEB_ROOT; ?></a></p>
</fieldset>
</form>