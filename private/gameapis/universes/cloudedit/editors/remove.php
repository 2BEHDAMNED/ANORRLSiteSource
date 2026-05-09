<?php
	use anorrl\Universe;
	use anorrl\User;
	
	header("Content-Type: application/json");

	if(!SESSION || !isset($universeId))
		die(http_response_code(503));

	$universe = Universe::FromID(intval($universeId));
	
	if(!$universe)
		die(http_response_code(503));
	
	$user = SESSION->user;

	if(!$universe->isOwner($user))
		die(http_response_code(503));

	$userToAdd = User::FromID(intval($_GET['userId']));
	if($userToAdd) {
		$universe->removeCloudEditor($userToAdd);
		echo "{}";
	}
?>
