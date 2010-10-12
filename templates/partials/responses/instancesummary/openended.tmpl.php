<form id="questionsform" class="wufoo ">
	<br>    
	<div class="lifix"> 
		<?php
		$resultcount = 1;
		if ($responses){
			foreach ($responses as $response){
				Partial::Render_Partial("responses/groupresponse", array("resultcount" => $resultcount++, "responses" => $response['responses'], "shownames" => $shownames, "name" => $response['name']));
			}
		}
		?>
	</div>
</form>