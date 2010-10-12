<div<?php
if (isset($id))	echo " id=\"$id\"";
?> class="noticebar grid_<?php echo $width;?> omega"><?php
	echo "<a href='#' onclick=\"$('#noticebar').hide('blind');\">Hide</a>";
	if (isset($title)) echo "<h3>$title</h3>";
	if (is_array($content)){
		foreach ($content as $c){
			echo "<p>$c</p>";
		}
	} else {
		echo "<p>$content</p>";
	}
	?></div>