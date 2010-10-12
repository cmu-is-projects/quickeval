<?php
Partial::Render_Partial("grid", array(
	"width" => 8, 
	"title" => "Contact", 
	"id" => "contentblock",
	"content" => new Partial("forms/contact_form")
	));
?>
