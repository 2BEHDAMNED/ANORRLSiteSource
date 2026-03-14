<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	if($user == null) {
		die(header("Location: /login"));
	}
?>
<!DOCTYPE html>
<html> 
	<head>
		<link rel="stylesheet" href="/css/new/main.css">
		<title>Home - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<script src="/js/core/jquery.js"></script>
		<script src="/js/main.js?t=1771413807"></script>
	</head>
	<body>
		<div id="Container" style="width:unset;margin:10px">

		</div>
	</body>
</html>