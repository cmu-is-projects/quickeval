<h2 class="clear"><?php echo $survey->name;?> : <strong><?php echo $projectteam->name;?></strong></h2>
<? if (User::can_access(User::U_TEACHER)) { ?>
<br>
<form action="/viewcrossinstances" method="GET">
	<input type="hidden" name="instances" value="<?php echo $_GET['instances'];?>" />
	<input type="hidden" name="group" value="<?php echo $_GET['group'];?>" />
<label class="desc huge" id="title17" for="Field17">
   What did
   <select name="whatdidxsay" id="responsename1" class="field select small"> 
		<option value="" <?php if ($_GET['whatdidxsay'] == "") echo 'selected="selected"';?>>Everyone</option> 
	   <?php if ($students) {
		    foreach ($students as $student){
		        ?><option <?php if ($_GET['whatdidxsay'] == $student->id) echo 'selected="selected"';?> value="<?php echo $student->id?>"><?php echo $student->name()?></option> <?php 
		     }
		}
		?>	
    </select> 
	say about
	<select name="abouty" id="responsename2" class="field select small"> 
			<option value="" <?php if ($_GET['abouty'] == "") echo 'selected="selected"';?>>Everyone</option> 
		   <?php if ($students) {
			    foreach ($students as $student){
			        ?><option <?php if ($_GET['abouty'] == $student->id) echo 'selected="selected"';?> value="<?php echo $student->id?>"><?php echo $student->name()?></option> <?php 
			     }
			}
			?>
	 </select>
	? <input type="submit" class="huge" value="OK" />
</label>
</form><br/>
<? } ?>

<?php
if ($instanceresults){
	$surveynum = 1;
?>
<table>
	<tr>
	<?php
	for ($i = 1; $i <= count($instanceresults); $i++){
		echo "<td align='center' style='font-size:18pt;'>Survey $i</td>";
	}
	?>
	</tr>
	<tr valign="top">
<?php
	foreach ($instanceresults as $irs){
		?>
		<td>
			<table>
			<form id="questionsform" class="wufoo ">
				<br>    
				<div class="lifix"> 
					<?php
					if ($irs){
						foreach ($irs as $response){
							echo "<tr><td>";
							echo $response;
							echo "</td></tr>";
						}
					}
					?>
				</div>
			</form>
			</table>
		</td>
		<?php
	}
?>
	</tr>
</table>
<?php
}

?>
