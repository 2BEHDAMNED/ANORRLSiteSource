<?php

	use anorrl\Alias;
	use anorrl\Universe;
	use anorrl\Asset;

	if(!SESSION || !isset($_GET['universeId']))
		die(http_response_code(403));

	$universe = Universe::FromID(intval($_GET['universeId']));

	if(!$universe)
		die(http_response_code(503));

	if(!$universe->isOwner(SESSION->user))
		die(http_response_code(503));

	$jsonstuff = json_decode(file_get_contents("php://input"));

	if(!$jsonstuff)
		die(http_response_code(500));

	$assetid = $jsonstuff->AssetId;

	$asset = Asset::FromID($assetid);

	if(!$asset)
		die(http_response_code(500));

	if(!$asset->isOwner(SESSION->user))
		die(http_response_code(503));

	$asset->setUniverse($universe);

	$alias_name = str_contains($jsonstuff->Name, "%") ? urldecode($jsonstuff->Name) : $jsonstuff->Name;
	
	if($asset->getAssetIDSafe() == $asset->id)
		$new_asset = $asset;
	else
		$new_asset = Asset::FromID($asset->getAssetIDSafe());
	

	Alias::Create($universe, $new_asset, $alias_name);
?>