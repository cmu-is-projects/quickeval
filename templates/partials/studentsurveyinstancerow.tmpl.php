	<li>
		<div class="classhead point open"><?php echo $course->name; ?>: <?php echo $group->name;?></div>
		<div class="classarea">
			<!--  HEADER -->
			<ul class="evalrow evalhead">
				<li>Evaluation Name</li> 
				<li class="postdate">Date Posted</li>
				<li class="duedate">Date Due</li>
				<?php 
				if ($students) {
					foreach ($students as $student){
						if ($student->id == User::current_user_id()) continue;
						?><li class="student"><acronym title="<?php echo $student->name();?>"><?php echo $student->initials();?></acronym></li><?php
					}
				}	
				?>
				<li>&nbsp;</li>
			</ul>
			<?php if ($instances){ 
				foreach ($instances as $instance){
					//get instance name
					try {
						$survey = new Survey($instance->survey_id);
						$surveyname = $survey->name;
					} catch (Exception $e){
						$surveyname = "Invalid Survey";
					}
					?>
					<ul class="evalrow">
					<li><?php echo $surveyname; ?></li> 
					<li class="postdate"><?php echo date("M j, Y", strtotime($instance->date_given));?></li>
					<li class="duedate"><?php echo date("M j, Y", strtotime($instance->closing_date));?></li>
					
					<?php 
					if ($students) {
						foreach ($students as $student){
							if ($student->id == User::current_user_id()) continue;
							if ($instance->has_student_completed_for($student->current_user_id(), $student->id) == SurveyInstance::SI_COMPLETED){
								$completed = "complete";
							} else {
								$completed = "incomplete";
							}
							if ($instance->isOpen()){
								$open = "open";
							} else {
								$open = "closed";
							}
							?>	<li class="student"><?php
							
							if ($open == "open"){
								echo "<a href='/fillsurvey?si_id=".$instance->id."&u_id=".$student->id."'>";
							}?><img class="icon" alt="<?php echo $open; ?>-<?php echo $completed;?>" src="ui/images/<?php echo $open; ?>-<?php echo $completed;?>.png" /><?php if ($open == "open"){
								echo "</a>";
							}?></li>
					<?php
						}
					}
					?>
					<?php
					if (!$instance->isOpen() && $instance->is_visible_after_close()){
					?>
					<li><a href="/viewresults?instance=<?php echo $instance->id;?>&group=<?php echo $group->id;?>"><img src="ui/images/viewresults.png" /> view results</a></li>
					<?php
					}
					?>
					</ul>
					<?php
				}
			}
			?>
			<?php
			$instancesstring = "";
			if ($instances){
				foreach ($instances as $instance){
					$instancestring .= $instance->id . ",";
				}
				?><div style="text-align:right;"><!--<a class="softbutton" href="/viewcrossinstances?instances=<?php echo substr($instancestring, 0, strlen($instancestring)-1);?>&group=<?php echo $group->id;?>"><img src="ui/images/overtime.png" alt="view results" title="view results"/> </a>--></div>
				<?php
			}
			?>
		</div>
	</li>
