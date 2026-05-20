<?php
	set_content_type(ARLTYPEJSON);

	echo json_encode([
		"message" => "hello!"
	]);
?>