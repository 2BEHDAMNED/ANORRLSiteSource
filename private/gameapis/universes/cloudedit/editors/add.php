<?php
	
	use anorrl\Universe;
	use anorrl\User;

	header("Content-Type: application/json");

	$usertoadd_id = intval($_GET['userId']);

	$universe = Universe::FromID(intval($universeId));
	$user = SESSION->user;

	if($universe && $user && $universe->isOwner($user)) {
		$userToAdd = User::FromID($usertoadd_id);
		if($userToAdd != null) {
			$universe->addCloudEditor($userToAdd);
			echo "{}";
		}
	}
?>
