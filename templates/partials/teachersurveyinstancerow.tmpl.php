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
					<ul style="width:100%;" class="evalrow">
					<li><a href="/questions?survey=<?php echo $instance->survey_id;?>"><?php echo $surveyname; ?></a></li> 
					<li class="postdate"><?php echo $instance->formatted_date_given();?></li>
					<li class="duedate" id="duedate_<?php echo $instance->id;?>_<?php echo $group->id;?>"><?php echo $instance->formatted_date_due();?></li>
					
					<?php 
					if ($students) {
						
						foreach ($students as $student){
							
							?>	<li class="student"><a href="#"><img class="icon studentdots" id="completeall_si_<?php echo $instance->id;?>_s_<?php echo $student->id;?>" src="ui/images/completionloader.gif" /></a></li>
					<?php
						}
					}
					?>
					<li style="width: 20px">&nbsp;</li>
					<li style="width: 30px;"><a class="softbutton" href="/viewresults?instance=<?php echo $instance->id;?>&group=<?php echo $group->id;?>&type=instancesummary"><img src="ui/images/viewresultssummary.png" alt="view instance summary" title="view instance summary"/> </a></li>         
					<li style="width: 30px;"><a class="softbutton" href="/viewresults?instance=<?php echo $instance->id;?>&group=<?php echo $group->id;?>"><img src="ui/images/viewresults.png" alt="view results" title="view results"/> </a></li>         
					<li style="width: 30px;"><a class="softbutton" href="/viewresults?instance=<?php echo $instance->id;?>&group=<?php echo $group->id;?>&type=csv"><img src="ui/images/csv.png" alt="Download CSV" title="Download CSV"/> </a></li>
					<li><a class="softbutton" href="javascript:extendDeadline(<?php echo $group->id;?>,<?php echo $instance->id;?>);"><img src="ui/images/extend.png" alt="extend deadline" title="extend deadline"/> </a></li> 
					</ul>
					<?php
				}
			}
			$instancesstring = "";
			if ($instances){
				foreach ($instances as $instance){
					$instancestring .= $instance->id . ",";
				}
				?>
				<div class="clear">&nbsp;</div>
				<div class="left">
				<a href="/viewcrossinstances?instances=<?php echo substr($instancestring, 0, strlen($instancestring)-1);?>&group=<?php echo $group->id;?>" class="softbutton"><img src="ui/images/viewovertime.png" />&nbsp;View Results Over Time</a>
				</div>
				<div class="clear">&nbsp;</div>
				<?php
			}
			?>
	</div>
		
	</li>
