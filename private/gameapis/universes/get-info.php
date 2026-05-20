<?php
	use anorrl\Universe;

	set_content_type(ARLTYPEJSON);

	$assetid = intval($_GET['universeId']);
	$universe = Universe::FromID($assetid);
	$place = $universe->starting_place;


	if($place != null) {

		echo json_encode([
			"CurrentUserHasEditPermissions" => true,
			"StudioAccessToApisAllowed" => true,
			"TargetId" => $universe->id,
			"ProductType" => "User Product",
			"AssetId" => $place->id,
			"ProductId" => $place->id,
			"Name" => $place->name,
			"Description" => $place->description,
			"AssetTypeId" => $place->type->ordinal(),
			"CreatorId" => $place->creator->id,
			"CreatorName" => $place->creator->id,
			"IconImageAssetId" => $place->id,
			"GameId" => $universe->id,
			"UniverseId" => $universe->id,
			"PlaceId" => $place->id,
			"openGameFromPlaceId" => $place->id,
			"updateFromPlaceId" => $place->id,
		]);

	} else {
		echo "{}";
	}
?>
