<?php
	use anorrl\Place;
	use anorrl\Universe;
	
	if(!SESSION || !isset($_GET['universeId']) || !isset($_GET['placeId']))
		die(http_response_code(403));

	$universe = Universe::FromID(intval($_GET['universeId']));
	$place = Place::FromID(intval($_GET['placeId']));

	if(!$universe || !$place)
		die(http_response_code(503));

	if(!$universe->isOwner(SESSION->user) || !$place->isOwner(SESSION->user) || $place->universe != $universe->id)
		die(http_response_code(403));

	$place->delete();
?>
