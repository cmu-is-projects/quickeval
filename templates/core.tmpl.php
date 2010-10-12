<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<!-- Info -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>QuickEval - <?php echo $title; ?></title>
<?php echo Partial::Render_Partial("metatags");?>
	<!-- CSS -->
	<link rel="stylesheet" href="/ui/css/style.css" type="text/css" />
	<link rel="stylesheet" href="/ui/css/errorboxes.css" type="text/css" />
	<link rel="stylesheet" href="/ui/css/960.css" type="text/css" />
	<link rel="stylesheet" href="/ui/css/smoothness/jquery-ui-1.7.2.custom.css" type="text/css" />
	<link rel="stylesheet" href="/ui/css/form.css" type="text/css" />
	<link rel="stylesheet" href="/ui/css/facebox.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/ui/css/search.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/ui/css/slider.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/ui/css/liveedit.css" type="text/css" media="screen" />
	<!-- JavaScript -->
	<?php Partial::Render_Partial("jstags", 
	array("scripts" => array(
		"jquery-1.3.2.min", 
		"jquery-ui-1.7.2.custom.min",
		"jquery.tablednd_0_5",
		"wufoo",
		"sevenup.0.2.min",
		"custom",
		"search",
		"facebox",
		"easySlider1.7",
		))); ?>
</head>
<body>
<div class="wrapper">
<?php 
foreach ($content as $c){
	echo $c;
}
?>
<div id="footerspacer">&nbsp;</div>
</div>

<?php Partial::Render_Partial("footer"); ?>

<div id="ajaxloadingindicator" style="display: none;">Loading...</div>
<?php Partial::Render_Partial("uservoice");?>
</body>
</html>