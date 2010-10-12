<div <?php echo (($resultcount % 2 == 0)?  "class='odd'" : "class='even'");?>>
  	<label class="desc" id="title2" for="hours"><span class="large"><?php echo $name;?></span></label>
	<br />
  	<table>
	<?php if (isset($responses)){
		$first = true;
		foreach ($responses as $response){
			if (!$shownames){
				//only show comments about ourselves
				if ($response->user_for->id != User::current_user_id()){
					continue;
				}
			}
			
			if (@$_GET['abouty'] != "" && User::can_access(User::U_TEACHER)){
				if ($response->user_for->id != $_GET['abouty']) continue;
			}

			$student = $response->user_for;
			
			if (!$first) {
				echo "<tr><td width='85px'><div class='hr'></div></td><td width='850px'><div class='hr'></div></td></tr>";
			}
			$first = false;
			echo "<tr><th valign='top' style='padding-right:10px;' width='85px'><img src='".Gravatar::getGravatarImageLocation($student->email, 50)."' alt='".$student->name()."' title='".$student->name()."' style='margin-bottom: 2px;' /><br/><span class='small'>".$student->name()."</span></th><td style='padding-bottom:10px;'>";

			foreach ($response->responses as $studentresponse){
				if ($studentresponse->user_from->id == $response->user_for->id) continue; //we dont have any data about what we said about ourself
				
				if (@$_GET['whatdidxsay'] != "" && User::can_access(User::U_TEACHER)){
					if ($studentresponse->user_from->id != $_GET['whatdidxsay']) continue;
				}
				
				$me = $studentresponse->user_from;

				if ($shownames){
					echo "<b>".$me->fname. " said:</b> ";
				} else {
					echo "<b>A peer said:</b> ";
				}
				
				//Handle Colors
				if ($isNumeric){
					if ($studentresponse->getNumericValue() < $response->getAverage()){
						$color = "red";
					} else if ($studentresponse->getNumericValue() > $response->getAverage()){
						$color = "green";
					} else {
						$color = "black";
					}
					
					echo "<span style='color:$color'>".$studentresponse->getString()."</span>";
				} else {
					echo $studentresponse->getString();
				}
				
				//Handle comments
				if ($studentresponse->comment){
					echo " Comment: ".$studentresponse->comment;
				}

				echo "<br/>";
			}
			if ($isNumeric){
				echo "<br/>Minimum: ".$response->getMinimum().", Maximum: ".$response->getMaximum().", Average: ";
				if ($response->getAverage() < $groupaverage){
					$color = "red";
				} else if ($response->getAverage() > $groupaverage){
					$color = "green";
				} else {
					$color = "black";
				}
				echo "<span style='font-weight: bold;color:$color;'>".$response->getAverage()."</span>";
			}
			echo "</td></tr>";
		}
	}?>
  	</table>
</div>