<?php
	use anorrl\Universe;

	header("Content-Type: application/json");
	if(isset($_GET['universeId'])) {
		$universe = Universe::FromID(intval($universeId));

		if($universe != null) {
			$places = [];

			foreach($universe->getAllPlaces() as $place) {
				$places[] = [
					"PlaceId" => $place->id,
					"Name" => $place->name
				];
			}

			die(json_encode([
				"FinalPage" => true,
				"RootPlace" => $universe->starting_place->id,
				"Places" => $places,
				"PageSize" => count($places)
			]));
		}
	}
?>
