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

	// thanks cubp
	die(json_encode([
		"GameBadges" => [
			[
				"BadgeAssetId" => 1,
				"PlaceId" => $place->id,
				"Name" => "Badge Name",
				"Thumbnail" => [
					"Url" => "",
				]
			]
		]
	]));
?>
