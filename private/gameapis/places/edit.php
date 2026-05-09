<?php
	use anorrl\Place;

	if(!SESSION || !isset($placeId))
		die(http_response_code(403));

	$place = Place::FromID($placeId);

	if(!$place)
		die(http_response_code(503));

	if(!$place->isEditable(SESSION->user))
		die(http_response_code(503));

	$jsonstuff = json_decode(file_get_contents("php://input"));

	if(!$jsonstuff)
		die(http_response_code(500));

	if(
		!isset($jsonstuff->ID) || 
		!isset($jsonstuff->AssetId) || 
		!isset($jsonstuff->ProductId) || 
		!isset($jsonstuff->TargetId) ||
		!isset($jsonstuff->Name))
			die(http_response_code(500));

	if(
		$jsonstuff->ID != $place->id ||
		$jsonstuff->AssetId != $place->id ||
		$jsonstuff->ProductId != $place->id ||
		$jsonstuff->TargetId != $place->id ||
		strlen(trim($jsonstuff->Name)) == 0)
			die(http_response_code(500));
	
	$place->renameTo($jsonstuff->Name)

?>