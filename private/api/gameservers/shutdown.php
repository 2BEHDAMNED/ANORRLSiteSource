<?php
	use anorrl\GameServer;
	use anorrl\Place;

	header("Content-Type: application/json");

	if(!SESSION || (!isset($_POST['serverID']) && !isset($_POST['placeID'])))
		die(json_encode([ "error" => true, "reason" => "You are not authorised to perform this action." ]));

	
	if(isset($_POST['serverID'])) {
		$gameserver = GameServer::Get($_POST['serverID']);
		if(!$gameserver)
			die(json_encode([ "error" => true, "reason" => "Gameserver not found."]));

		if($gameserver->place->isOwner(SESSION->user)) {
			$gameserver->shutdown();
			die(json_encode([ "error" => false ]));
		}
		else 
			die(json_encode([ "error" => true, "reason" => "You are not authorised to perform this action." ]));
	} else if(isset($_POST['placeID'])) {
		$place = Place::FromID(intval($_POST['placeID']));

		if(!$place)
			die(json_encode([ "error" => true, "reason" => "Place not found."]));

		if(!$place->isOwner(SESSION->user))
			die(json_encode([ "error" => true, "reason" => "You are not authorised to perform this action." ]));

		foreach($place->getServers() as $server) {
			$server->shutdown();
		}

		die(json_encode([ "error" => false ]));
	}
	
	die(json_encode([ "error" => true, "reason" => "Something went wrong!" ]));
?>