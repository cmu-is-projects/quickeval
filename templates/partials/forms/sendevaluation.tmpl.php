<h2>Send Evaluation</h2>
<form name="sendevaluation" id="sendevaluation" class="wufoo " enctype="multipart/form-data" action="/data.php" method="POST">
	<input type="hidden" name="controller" value="surveyinstances" />
	<input type="hidden" name="action" value="create" />
<ul>
	<li id="foli7" class=" ">
	<label class="desc" id="title17" for="Field17">Course<span class="req">*</span></label>
		<div>
			<select id="courseid" name="courseid" class="field select large"> 
				<?php
				$c_f = new Course();
				$courses = $c_f->find(array("owner_id" => User::current_user_id()));
				if ($courses){
					foreach ($courses as $course){
						echo "<option value='".$course->id."'";
						if ($curcourseid == $course->id) echo " selected";
						echo ">".$course->name."</option>";
					}
				}
				?>
			</select>
		</div>
	</li>
	<li id="foli7" class=" ">
		<label class="desc" id="title17" for="Field17">Evaluation<span class="req">*</span></label>
		<div>
			<select id="evaluationid" name="surveyid" class="field select large"> 
				<?php
				$s_f = new Survey();
				$evaluations = $s_f->find(array("owner_id" => User::current_user_id()));

				foreach ($evaluations as $evaluation){
					echo "<option value='".$evaluation->id."'";
					echo ">".$evaluation->name."</option>";
				}
				?>
			</select>
		</div>
	</li>
	<?php
	$duedate = time() + (60*60*24*7);
	?>
	<li id="foli20" class="    ">
		<label class="desc" id="title20" for="Field20">Date Due<span id="req_20" class="req">*</span></label>
		<span>
			<input id="Field20-1" name="due_m" type="text" class="field text" value="<?php echo date("m", $duedate);?>" size="2" maxlength="2" tabindex="4" /> /
			<label for="Field20-1">MM</label>
		</span>
		<span>
			<input id="Field20-2" name="due_d" type="text" class="field text" value="<?php echo date("d", $duedate);?>" size="2" maxlength="2" tabindex="5" /> /
			<label for="Field20-2">DD</label>
		</span>
		<span>
		 	<input id="Field20" name="due_y" 	type="text" class="field text" value="<?php echo date("Y", $duedate);?>"	size="4" maxlength="4" tabindex="6" />
			<label for="Field20">YYYY</label>
		</span>
	</li>
	<li id="foli7" class=" ">
	<label class="desc" id="title17" for="Field17">Visibility after close<span class="req"></span></label>
		<div>
			<select id="closevisibility" name="closevisibility" class="field select large"> 
				<option value="0">Anonymized Entire Results Available</option>
				<!--<option value="1">Anonymized Numeric Only Results Available</option>-->
				<option value="2">No Results Available</option>
			</select>
		</div>
	</li>
	<li>
		<input id="sendevaluationbutton" type="submit" onclick="this.value='Sending evaluation.... Please Wait';" class="btTxt" value="Send" tabindex="2"/>
	</li>
	
</ul>
</form>