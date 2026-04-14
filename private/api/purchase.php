<?php
	use anorrl\Asset;
	
	header("Content-Type: application/json");

	if(!SESSION)
		die(json_encode(["error" => true, "message" => "User is not logged in."]));


	$user = SESSION->user;
	if(!$user->isBanned() && isset($_POST['id'])) {
		$asset = Asset::FromID(intval($_POST['id']));

		if(!$asset)
			die(json_encode(["error" => true, "message" => "Invalid purchase method."]));
		
		die(json_encode($asset->purchase(/*$type, */$user)));
	} else {
		die(json_encode(["error" => true, "message" => "User is not authorised to perform this action."]));
	}

?>
