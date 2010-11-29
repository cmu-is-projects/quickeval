<div<?php
if (isset($id))	echo " id=\"$id\"";
?> class="grid_<?php echo $width;?> <?php if(isset($type)) echo $type; ?>"><?php
	if (isset($button)) {
		echo "<div class='right'>";
		if (isset($button2)) {
			echo Partial::Render_Partial("iconbutton", array("id" => "addgroup", "text" => $button2));		
		}
		echo Partial::Render_Partial("iconbutton", array("id" => "showadd", "text" => $button));
		echo "</div>";
	}
	if (isset($evalbutton)) {
		echo "<div class='right'>";
		echo Partial::Render_Partial("iconbutton", array("id" => "sendevaluation", "text" => "Send Evaluation", "icon" => "email", "linked" => "#sendeval", "rel" =>"facebox"));
		echo "</div>";
	}
	if (isset($title)) echo "<h2>$title</h2>";
	if (is_array($content)){
		foreach ($content as $c){
			echo "<p>$c</p>";
		}
	} else {
		echo "<p>$content</p>";
	}
	?></div>
