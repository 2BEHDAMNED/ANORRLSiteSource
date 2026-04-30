<?php
	use anorrl\Place;

	header("Content-Type: application/json");

	$place_id = intval($universeId);

	$place = Place::FromID($place_id);
	$user = SESSION->user;

	if($place != null && $user != null && ($user->id == $place->creator->id || $user->isAdmin())) {
		$place->enableTeamCreate();
		echo "{}";
	}
?>
