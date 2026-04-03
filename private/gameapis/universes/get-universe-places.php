<?php
	use anorrl\Place;

	header("Content-Type: application/json");
	if(isset($universeId)) {
		$place = Place::FromID(intval($universeId));

		if($place != null) {
			die(json_encode([
				"FinalPage" => true,
				"RootPlace" => $place->id,
				"Places" => [
					"PlaceId" => $place->id,
					"Name" => $place->name
				],
				"PageSize" => 1
			]));
		}
	}
?>