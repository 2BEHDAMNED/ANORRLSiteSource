<?php
	set_content_type(ARLTYPEJSON);
	
	die(json_encode([
		"DeveloperProducts" => [],
		"FinalPage" => true,
		"PageSize" => 50
	]));
?>