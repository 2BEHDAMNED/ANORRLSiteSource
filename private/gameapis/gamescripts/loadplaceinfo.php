<?php
	use anorrl\Place;
	use anorrl\Script;
	use anorrl\utilities\ClientDetector;

	set_content_type(ARLTYPEPLAIN);

	if(!isset($_GET['PlaceId']) || !ClientDetector::HasAccess())
		die(http_response_code(403));

	$place = Place::FromID(intval($_GET['PlaceId']));

	if(!$place)
		die(http_response_code(403));

	die(new Script("loadplaceinfo")->sign([
		"creator" => $place->creator->id
	]));
		
?>