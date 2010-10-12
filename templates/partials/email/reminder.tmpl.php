<img src='<?php echo WEB_ROOT; ?>/images/email/signup.jpg' align='right' alt='Reminder' />    
<?php if ($user->level == User::U_INVITED) { ?> 
Hold Up! You haven't even activated your account yet!  Please click this link to activate your account: 
<a href="<?php echo WEB_ROOT; ?>signup?key=<?php echo urlencode($user->invite_code); ?>&email=<?php echo urlencode($user->email); ?>">Activate Account</a>
<br />
After you have activated your account, please continue reading:
<br />
<br />
<?php } ?>

Hey <?php echo $user->name(); ?>, your peer evaluation for the course <?php echo $course->name; ?> is due in 24 hours!  
<br />
<br />
If you have already completed your survey, give yourself a pat on the back.  
<br />
<br />
Otherwise, you should head over to <a href='<?php echo WEB_ROOT; ?>'><?php echo WEB_ROOT; ?></a>, log in, and complete your peer evaluation

