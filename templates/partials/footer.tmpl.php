<div id="footer">
	<?php if (User::can_access(User::U_STUDENT)) { ?>
	<p>
		<a href="/about">About Us</a>&nbsp;&nbsp;|&nbsp;&nbsp;
		<a href="/contact">Contact</a>&nbsp;&nbsp;|&nbsp;&nbsp;
		<a href="/privacy">Privacy Policy</a>&nbsp;&nbsp;|&nbsp;&nbsp;
		<a href="/termsofuse">Terms of Use</a>&nbsp;&nbsp;|&nbsp;&nbsp;
		<a href="/faq">FAQ</a>
	</p>
	<?php } ?>
	  
	 
	<div class="fwrapper">
		<a href="http://is.hss.cmu.edu" title="Information Systems"><div class="infosys">&nbsp;</div></a>
		<a href="http://www.twitter.com/quickeval" title="Twitter"><div class="twitter">&nbsp;</div></a>
		<a href="http://www.facebook.com/home.php?#/pages/QuickEval/178231018575?ref=search&sid=4804495.4289399316..1" title="Facebook"><div class="facebook">&nbsp;</div></a>   
		<?php if (User::can_access(User::U_STUDENT)) { ?>
			<p style="color:#484848;">
				<?php echo "Server Time " . date("g:i:s A,  l jS, Y T");?>
			</p>
		<?php } ?>
	</div>
</div>
