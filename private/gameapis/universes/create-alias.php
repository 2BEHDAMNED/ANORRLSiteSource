<?php

	use anorrl\Alias;
	use anorrl\Universe;
	use anorrl\Asset;

	if(!SESSION || !isset($_GET['universeId']))
		die(http_response_code(403));

	$universe = Universe::FromID(intval($_GET['universeId']));

	if(!$universe)
		die(http_response_code(503));

	$jsonstuff = json_decode(file_get_contents("php://input"));

	if(!$jsonstuff)
		die(http_response_code(500));

	$assetid = $jsonstuff->AssetId;

	$asset = Asset::FromID($assetid);

	if(!$asset)
		die(http_response_code(500));

	$asset->setUniverse($universe);

	$alias_name = str_contains($jsonstuff->Name, "%") ? urldecode($jsonstuff->Name) : $jsonstuff->Name;
	
	Alias::Create($universe, $asset, $alias_name);
?>