<?php
	use anorrl\Universe;

	header("Content-Type: application/json");
	
	if(!isset($_GET['universeId']))
		die("{}");

	$universe = Universe::FromID(intval($_GET['universeId']));

	if(!$universe)
		die("{}");

	$aliases = [];

	foreach($universe->getAliases() as $alias) {
		$asset = $alias->asset;
		$aliases[] = [
			"Name" => $alias->name,
			"Type" => 1,
			"TargetId" => $alias->id,
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
		"PageSize" => count($aliases)
	]);
?>
