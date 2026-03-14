<?php

	if(isset($_GET['placeId'])) {
		$placeid = intval($_GET['placeId']);
	} else {
		$placeid = intval($_GET['placeid']);
	}
	

	

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/assetutils.php";

	$place = Place::FromID($placeid);

	if($place != null) {
		echo json_encode([
			"UniverseId" => $placeid,
			"GameId" => $placeid,
			"PlaceId" => $placeid,
			"openGameFromPlaceId" => $placeid,
			"updateFromPlaceId" => $placeid,
		]);
	}
	

?>