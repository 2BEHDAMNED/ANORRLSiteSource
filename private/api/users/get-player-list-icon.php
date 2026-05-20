<?php
	use anorrl\User;
	use anorrl\enums\AssetType;
	$userid = null;
	if(isset($_GET['userId'])) {
		$userid = intval($_GET['userId']);
	} else {
		if(SESSION)
			$userid = SESSION->user->id;
	}
	if(!$userid) {
 	   echo json_encode(["error" => true, "reason" => "the fuck is ur user doofus?"]);
       exit;
	}
	$user = User::FromID($userid);
    $icon = $user->getPlayerListIcon();
	echo json_encode($icon ? $icon->id : null);
