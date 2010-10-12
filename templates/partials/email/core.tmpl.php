<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>QuickEval</title>
</head>
<body style="margin: 0; background-color: #ffffff; padding: 0;">
	<table cellspacing="0" border="0" cellpadding="0" width="100%">
		<tr>
			<td align="center">
				<table cellspacing="0" border="0" cellpadding="0" width="646">
					<tr>
						<td><img src="<?php echo WEB_ROOT;?>images/email/header.jpg" height="163" alt="Header" width="646" /></td>
					</tr>
				</table>
				<table cellspacing="0" border="0" cellpadding="0" width="580">
					<tr>
						<td>
							<table class="content" cellspacing="16" border="0" height="130" style="background-color: #f5f5f5;" cellpadding="0" width="580">
								<tr>
									<td class="mainbar" align="left" valign="top" width="580">

										<h3 style="font-size: 16px; font-weight: normal; margin: 10px 0 14px 0; font-family: Georgia, serif; color: #3B4868; padding: 0;"><?php echo date("l F j, Y"); ?></h3>
										<?php 
										if (is_array($content)){
											foreach ($content as $c){
												echo $c;
											}
										} else { 
											echo $content;
										}
										?>
										<?php Partial::Render_Partial("email/thanksfooter");?>
									</td>
								</tr>

							</table>
						</td>
					</tr>
				</table>
				<table cellspacing="0" border="0" cellpadding="0" width="646">
					<tr>
						<td><img src="<?php echo WEB_ROOT;?>images/email/footer-tail.jpg" height="87" alt="Footer" width="646" /></td>
					</tr>
					<tr>
						<td class="footer" align="center" style="padding: 20px 0 20px 0;">
							<p style="font-size: 10px; font-weight: normal; font-family: 'Lucida Grande', sans-serif; color: #151515;">&copy; <?php echo date("Y"); ?> QuickEval<br />QuickEval - Porter Hall 222, 5000 Forbes Avenue, Pittsburgh, PA 15213</p></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

</body>
</html>
