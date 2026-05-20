<?php
	use anorrl\User;
	use anorrl\UserSettings;

	$userid = null;
	if(isset($_GET['userId'])) {
		$userid = intval($_GET['userId']);
	} else {
		if(SESSION)
			$userid = SESSION->user->id;
	}
	if(!$userid) {
		echo json_encode([
			"error" => true,
			"reason" => "the fuck is ur user doofus?"
		]);
		exit;
	}

	// even if the user is null, it will return a null asset by default
	$user_settings = UserSettings::Get(User::FromID($userid));
	$icon = $user_settings->playerlisticon;

	die(json_encode([
		"error" => false,
		"icon" => $icon ? $icon->id : -1
	]));
?>
