<?php
	use anorrl\Place;
	use anorrl\utilities\ClientDetector;

	if(!isset($placeId) || !isset($type))
		die(header("Location: /create/"));

	if($type != "badge")
		die(header("Location: /create/"));

	$place = Place::FromID($placeId);

	if(!$place)
		die(header("Location: /create/"));

	if(SESSION->user->id != $place->creator->id)
		die(header("Location: /{$place->getURL()}"));

	if(!ClientDetector::IsAClient() || true) { // lets see how well it is on studio
		require "badge_views/normal.php";
	}
	else {
		require "badge_views/studio.php";
	}

?>
