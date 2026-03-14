<?php
header('Content-type: application/json');
require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/assetutils.php";

$place = Place::FromID(intval($_GET['placeId']));

if($place != null) {
	echo json_encode([
		"AssetId" => $place->id,
		"ID" => $place->id,
		"Creator" => [
			"Id" => $place->creator->id,
			"Name" => $place->creator->name,
			"CreatorTargetId" => $place->creator->id,
			"CreatorType" => 0
		],
		"GameId" => $place->id,
		"UniverseId" => $place->id,
		"PlaceId" => $place->id,
		"openGameFromPlaceId" => $place->id,
		"updateFromPlaceId" => $place->id,
	]);
} else {
	echo "{}";
}

?>