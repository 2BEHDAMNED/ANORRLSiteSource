<?php
	use anorrl\User;
	use anorrl\utilities\UtilUtils;

	if(!UtilUtils::HasBeenRewritten()) {
		die(header("Location: /my/home"));
	}

	// No id parameter? GET OUT!
	if(!isset($id)) {
		die(header("Location: /my/home"));
	}

	$get_user = User::FromID(intval($id));

	if($get_user == null) {
		die(header("Location: /my/home"));
	}

	$header_data = $get_user;

	header("Content-Type: text/css");
	
	if(UtilUtils::IsValidCSS(SESSION->settings->css) || isset($_GET['force'])) {
		die(SESSION->settings->css);
	}

	die();
?>
