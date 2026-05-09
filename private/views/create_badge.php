<?php
	use anorrl\Place;
	use anorrl\utilities\ClientDetector;

	if(!isset($placeId))
		die(header("Location: /create/"));

	$place = Place::FromID($placeId);

	if(!$place)
		die(header("Location: /create/"));

	if(SESSION->user->id != $place->creator->id)
		die(header("Location: /create"));

	if(!ClientDetector::IsAClient()) {
		require "badge_views/normal.php";
	}
	else {
		require "badge_views/studio.php";
	}

?>
