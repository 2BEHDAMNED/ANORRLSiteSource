<?php
	/**
	 *  NO.
	 *  This is not where you create places, rather this is to create ASSETS (like badges) **FOR** places.
	 */

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

	if(!ClientDetector::IsAClient()) {
		require "placecreate_views/normal.php";
	}
	else {
		require "placecreate_views/studio.php";
	}

?>
