<div id="content" class="container_12">
	<div class="grid_9" id="breadcrumb">
		<a href="/">Quickeval.org</a> <?php
		if (!isset($breadcrumb)){
			echo "&raquo; ";
			echo $title;
		} else if (is_array($breadcrumb)){
			foreach ($breadcrumb as $name=>$link){
				echo "&raquo; ";
				if ($link == null){
					echo $name;
				} else {
					echo "<a href=\"$link\" alt=\"$name\">$name</a> ";
				}
			}
		} else {
			echo "&raquo; ";
			echo $breadcrumb;
		}
		?>
	</div>
	
	<div class="grid_3" class="right">
		<?php
		foreach ($sidebar as $s){
			if (is_object($s)){
				echo $s->fetch();
			} else {
				echo $s;
			}
		}
		?>
	</div>
	
	<?php
	if (@$_SESSION['noticeflash']){
		$out = "<ul>";
		foreach ($_SESSION['noticeflash'] as $notice){
			$out .= "<li>$notice</li>";
		}
		$out .= "</ul>";
		Partial::Render_Partial("notice", array("id" => "noticebar", "title" => "Notice", "content" => $out, "width" => 12));
		unset($_SESSION['noticeflash']);
	}
	?>	<?php
	if (@$_SESSION['errorflash']){
		$out = "<ul>";
		foreach ($_SESSION['errorflash'] as $error){
			$out .= "<li>$error</li>";
		}
		$out .= "</ul>";
		Partial::Render_Partial("error", array("id" => "errorbar", "title" => "Error", "content" => $out, "width" => 12));
		unset($_SESSION['errorflash']);
	}
	?>
	
	<!-- Content Starts -->
	<?php
	if (is_array($content)){
		foreach ($content as $c){
			echo $c;
		}
	} else {
		echo $content;
	}
	?>
	
</div>