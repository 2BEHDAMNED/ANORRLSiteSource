<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();
	if($user != null) {
		UserUtils::RemoveCookies();
		session_destroy();
		echo "Logged out yay";
	} else {
		echo "Why even perform this when you aren't even logged in??";
	}
	
?>