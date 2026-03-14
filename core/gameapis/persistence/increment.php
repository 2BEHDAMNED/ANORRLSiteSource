<?php
//	require_once($_SERVER['DOCUMENT_ROOT'] . '/include/sqldbcon.php');
//	require_once($_SERVER['DOCUMENT_ROOT'] . '/include/functions.php');
	
	header("Content-Type: application/json");
	http_response_code(501);
	exit(json_encode(["error"=>"Not Implemented"]));
?>
