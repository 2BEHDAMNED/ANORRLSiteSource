<?php
	use anorrl\Place;
	use anorrl\Script;
	use anorrl\utilities\ClientDetector;

	header("Content-Type: text/plain");

	if(!isset($_GET['PlaceId']) || !ClientDetector::HasAccess())
		die(http_response_code(403));

	$place = Place::FromID(intval($_GET['PlaceId']));

	if(!$place)
		die(http_response_code(403));

	die(new Script("loadplaceinfo")->sign([
		"creator" => $place->creator->id
	]));
		
?>