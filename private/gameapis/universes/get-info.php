<?php
use anorrl\Universe;
header('Content-type: application/json');


$assetid = intval($_GET['universeId']);
$universe = Universe::FromID($assetid);
$asset = $universe->starting_place;

if($asset != null) {

	echo json_encode([
		"CurrentUserHasEditPermissions" => true,
		"StudioAccessToApisAllowed" => true,
		"TargetId" => $assetid,
		"ProductType" => "User Product",
		"AssetId" => $assetid,
		"ProductId" => $assetid,
		"Name" => $asset->name,
		"Description" => $asset->description,
		"AssetTypeId" => $asset->type->ordinal(),
		"CreatorId" => $asset->creator->id,
		"CreatorName" => $asset->creator->id,
		"IconImageAssetId" => $assetid,
		"GameId" => $asset->id,
		"UniverseId" => $asset->id,
		"PlaceId" => $asset->id,
		"openGameFromPlaceId" => $asset->id,
		"updateFromPlaceId" => $asset->id,
	]);

} else {
	echo "{}";
}

?>
