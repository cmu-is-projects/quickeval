<?php
if (!isset($clear)) Partial::Render_Partial("clear");
if (!isset($height)) $height = 2;
for ($i = 0; $i < $height; $i++){
	Partial::Render_Partial("grid", array("width" => 8, "content" => "&nbsp;"));
}
?>