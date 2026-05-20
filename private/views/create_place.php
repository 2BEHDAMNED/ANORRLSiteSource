<?php
	/**
	 *  NO.
	 *  This is not where you create places, rather this is to create ASSETS (like badges) **FOR** places.
	 */

	use anorrl\Place;
	use anorrl\utilities\ClientDetector;

	if(!isset($placeId) || !isset($type))
		redirect("/create/");

	if($type != "badge")
		redirect("/create/");

	$place = Place::FromID($placeId);

	if(!$place)
		redirect("/create/");

	if(SESSION->user->id != $place->creator->id)
		redirect("/{$place->getURL()}");

	if(!ClientDetector::IsAClient()) {
		require "placecreate_views/normal.php";
	}
	else {
		require "placecreate_views/studio.php";
	}

?>
