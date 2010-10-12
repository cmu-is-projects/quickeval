<li>
	<table cellpadding="0" cellspacing="0" summary="Results from Question #" border="0">
<?php
	$horizontal_names = array();
	if (!isset($response['responses'])) die("No responses for question");
	foreach ($response['responses'][0]->responses as $participating_users){
		$horizontal_names[] = $participating_users->user_from;
	}
	$columnsums = array();
	$comments = array();
	
?>
<h3 class="large"><?php echo $response['name']; ?></h3>
<thead>
	<tr height="60">
		<th class="tfirst">&nbsp;</th>
		<?php
		$count = 0;
		foreach ($horizontal_names as $name){
			echo "<th>".$name->name()."<br />said</th>";
			$columnsums[$count++] = array();
		}
		?>
		<th class="tavg">Student<br />Averages</th>
	</tr>
</thead>
<tbody>
<?php
	foreach ($response['responses'] as $response){
		echo "<tr height='60'>";
		echo "<th>".$response->user_for->htmlAcronym()."</th>";
		$numbers = 0;
		$sumcount = 0;
		$count = 0;
		foreach ($response->responses as $subresponse){
			if ($response->user_for->id == $subresponse->user_from->id){
				//we are ourselves - leave it blank
				echo "<td class='tavg'>&nbsp;</td>";
			} else {
				echo "<td>".$subresponse->getString()."</td>";
				if ($subresponse->hasValue()) {
					$columnsums[$count][] = $subresponse->getNumericValue();
					$numbers += $subresponse->getNumericValue();
					$sumcount++;
				}
			}
			if ($subresponse->hasComment()){
				$comments[] = $subresponse->user_from->name() . " said this about " . $response->user_for->name(). ": &quot;" . $subresponse->comment . "&quot;";
			}
			$count++;
		}
		echo "<td class='tavg'>";
		if ($sumcount == 0){
			echo "N/A";
		} else {
			echo number_format(round($numbers / $sumcount, 2),2);
		} 
		echo "</td>";
		echo "</tr>";
	}	
?>
		</tbody>
		<tfoot> 
			<tr height='60'>
				<th>Avg</th>     <!-- individual average for each group member for this question -->
				<?php
				$count = 0;
				foreach ($horizontal_names as $name){
					echo "<td>";
						if (count($columnsums[$count]) != 0){
							echo number_format(round(array_sum($columnsums[$count]) / count($columnsums[$count]), 2),2);
						} else {
							echo "N/A";
						}
					echo "</td>";
					$count++;
				}
				?>
			</tr>      
		</tfoot>	           
	</table>
	<?php
	if (count($comments) > 0){
		echo "<br><h3>Comments</h3><div>";
		foreach ($comments as $comment){
			echo "$comment<br />";
		}
		echo "</div>";
	} 
	?>
</li>