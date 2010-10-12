<!-- Numerical and Rating Questions and colored accordingly -->
<div id="slider">
	<ul>
<?php
	if ($responses){
		foreach ($responses as $response){
			echo Partial::Render_Partial("responses/instancesummary/numericslidertab",array("response" => $response));
		}
	}
?>
	</ul>
</div>
