<?php

	use anorrl\Alias;
	use anorrl\Universe;
	use anorrl\Asset;

	if(!SESSION || !isset($_GET['universeId']))
		die(http_response_code(403));

	$universe = Universe::FromID(intval($_GET['universeId']));

	if(!$universe)
		die(http_response_code(503));

	if(!$universe->hasAccess(SESSION->user))
		die(http_response_code(503));

	$jsonstuff = json_decode(file_get_contents("php://input"));

	if(!$jsonstuff)
		die(http_response_code(500));

	$asset = Asset::FromID($jsonstuff->Asset->Id);
	$alias = Alias::FromID($jsonstuff->TargetId);

	if(!$asset || !$alias)
		die(http_response_code(500));

	if(!$asset->isOwner(SESSION->user))
		die(http_response_code(503));

	if($alias->asset->id != $asset->id)
		die(http_response_code(500));

	$name = $jsonstuff->Name;

	if(!str_contains($name, "/"))
		die(http_response_code(500));

	if(strcmp($name, $alias->name) == 0)
		die(http_response_code(500));

	$alias->renameTo($name)

?>