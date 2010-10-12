Hello <?php echo $inviteduser->name(); ?>,

You have been invited by your professor, <?php echo $invitinguser->name(); ?>, to 
QuickEval.org, the premier online peer evaluation system.  

Please click this link to activate your account:
<?php echo WEB_ROOT;?>signup?key=<?php echo $inviteduser->invite_code; ?>&email=<?php echo $inviteduser->email; ?>

Thank You,
The QuickEval Team
