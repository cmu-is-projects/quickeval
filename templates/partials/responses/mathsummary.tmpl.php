<div <?php echo (($resultcount % 2 == 0)?  "class='odd'" : "class='even'");?>>
	<div style="text-align:right;">
	<?php 
	if ($responses['isNumeric'] == true){
		echo "Total Minimum: ".$responses->getMinimum().", Total Maximum: ".$responses->getMaximum().", Total Average: ".$responses->getAverage();
	}
	?>	
	</div>
</div>