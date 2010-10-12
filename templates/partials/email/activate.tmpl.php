<img src='<?php echo WEB_ROOT; ?>/images/email/signup.jpg' align='right' alt='Sign Up' />

You have been invited by your professor, <?php echo $invitinguser->name(); ?> for the course, <?php echo $course->name; ?>, to QuickEval.org, the premier online peer evaluation system.  <br /><br />

Please click this link to activate your account:<br /><a href="<?php echo WEB_ROOT; ?>signup?key=<?php echo urlencode($inviteduser->invite_code); ?>&email=<?php echo urlencode($inviteduser->email); ?>">Activate Account</a>
