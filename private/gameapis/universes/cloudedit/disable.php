<?php
	use anorrl\Universe;
	
	header("Content-Type: application/json");

	$universe = Universe::FromID(intval($universeId));
	$user = SESSION->user;

	if($universe && $user && $universe->isOwner($user)) {
		$universe->disableTeamCreate();
		echo "{}";
	}
?>
