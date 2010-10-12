<img src='<?php echo WEB_ROOT; ?>/images/email/forgotpassword.jpg' align='right' alt='New User Signup' />

Hooray!, the QuickEval team has approved your request to sign up as a professor.
<br /><br />

We went ahead and created a professor account for you already.  Here are the details: 
<br /><br />
<strong>Name:</strong> <?php echo $user->name();?><br />
<strong>Login:</strong> <?php echo $user->email; ?><br />
<strong>Password:</strong> <?php echo $password; ?><br />
<br />
<br />
To start creating your evaluations to send to your courses, go to <?php echo WEB_ROOT; ?> and log in with these credentials.  <br /><br />
After logging in, you probably should change your password to something more memorable.  To do that, after you log in, click the My Settings tab on the top of the page.