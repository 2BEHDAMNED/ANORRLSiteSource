<?php

use anorrl\Universe;
use anorrl\enums\AssetType;
	header("Content-Type: application/json");
	
	if(!isset($_GET['universeId']))
		die("{}");

	$universe = Universe::FromID(intval($_GET['universeId']));

	if(!$universe)
		die("{}");

	$aliases = [];

	// todo: actually implement aliases (table done on prod)
	foreach($universe->getDeveloperProducts(AssetType::DECAL) as $asset) {
		$aliases[] = [
			"Name" => $asset->name,
			"Type" => 1,
			"TargetId" => $asset->id,
			"Asset" => [
				"Id" => $asset->id,
				"TypeId" => $asset->type->ordinal(),
				"Name" => $asset->name,
				"Description" => $asset->name,
				"CreatorType" => 1,
				"CreatorTargetId" => $asset->creator->id,
				"Created" => "2017-03-31T12:16:46.547",
				"Updated" => "2017-08-29T08:50:09.317"
			],
			"Version" => null
		];
	}

	echo json_encode([
		"FinalPage" => true,
		"Aliases" => $aliases,
		"PageSize" => 50
	]);

	/*{
		"FinalPage": true,
		"Aliases": [{
			"Name": "Scripts/Init",
			"Type": 1,
			"TargetId": 718028943,
			"Asset": {
				"Id": 718028943,
				"TypeId": 5,
				"Name": "Script",
				"Description": "Script",
				"CreatorType": 1,
				"CreatorTargetId": 4719353,
				"Created": "2017-03-31T12:16:46.547",
				"Updated": "2017-08-29T08:50:09.317"
			},
			"Version": null
		}],
		"PageSize": 50
	}*/
?>
