<?php
	use anorrl\Place;
	use anorrl\Universe;
	
	if(!SESSION || !isset($_GET['universeid']) || !isset($_GET['placeid']))
		die(http_response_code(403));

	$universe = Universe::FromID(intval($_GET['universeid']));
	$place = Place::FromID(intval($_GET['placeid']));

	if(!$universe || !$place)
		die(http_response_code(503));

	if(!$universe->isOwner(SESSION->user) || !$place->isOwner(SESSION->user) || $place->universe != $universe->id)
		die(http_response_code(403));

	$result = $universe->setStartingPlace($place);

	if(!$result)
		die(http_response_code(503));
?>
