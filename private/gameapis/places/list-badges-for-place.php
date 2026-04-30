<?php
	use anorrl\Place;

	if(!isset($_GET['placeId']) || !SESSION)
		die(http_response_code(503));

	header("Content-Type: application/json");

	$place = Place::FromID(intval($_GET['placeId']));
	
	if(!$place)
		die(http_response_code(503));

	if(!$place->isEditable(SESSION->user))
		die(http_response_code(503));
	
	die(json_encode([
		"PlaceId" => $place->id,
		"totalItems" => 1,
		"IsViewerPlaceOwner" => SESSION->user->id == $place->creator->id,
		"data" => [
			[
				"BadgeId" => 1,
				"Url" => ""
			]
		]
	]));
?>
