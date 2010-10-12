<img src='<?php echo WEB_ROOT; ?>/images/email/forgotpassword.jpg' align='right' alt='Reset Password' />

You (or someone else) has requested that your password be reset.  If you did not make such a request, please disregard this email. <br /><br />

Otherwise, <a href="<?php echo WEB_ROOT; ?>resetpassword?key=<?php echo urlencode($user->invite_code); ?>&email=<?php echo urlencode($user->email); ?>">Click Here</a> to reset your password.
