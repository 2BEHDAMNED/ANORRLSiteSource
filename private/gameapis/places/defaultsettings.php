<?php
	set_header("Content-Type", "application/json");

	echo json_encode([
		"message" => "hello!"
	]);
?>