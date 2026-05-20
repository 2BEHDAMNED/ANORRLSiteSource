<?php
	use anorrl\Asset;

	set_content_type(ARLTYPEJSON);
	
	if(isset($_GET['productId'])) {
		$assetid = intval($_GET['productId']);
	} else if(isset($_GET['assetId'])) {
		$assetid = intval($_GET['assetId']);
	} else {
		die();
	}

	$asset = Asset::FromID($assetid);

	if($asset != null) {
		die(json_encode([
			"TargetId" => $assetid,
			"ProductType" => "User Product",
			"AssetId" => $assetid,
			"ProductId" => $assetid,
			"Name" => $asset->name,
			"Description" => $asset->description,
			"AssetTypeId" => $asset->type->ordinal(),
			"Creator" => [
				"Id" => $asset->creator->id,
				"Name" => $asset->creator->name,
				"CreatorType" => "User",
				"CreatorTargetId" => $asset->creator->id
			],
			"IconImageAssetId" => $assetid,
			"Created" => "2007-05-30T07:05:24.057Z",
			"Updated" => "2013-08-06T17:49:26.167Z",
			"PriceInRobux" => 0,
			"PremiumPriceInRobux" => 0,
			"PriceInTickets" => null,
			"IsNew" => false,
			"IsForSale" => false,
			"IsPublicDomain" => true,
			"IsLimited" => false,
			"IsLimitedUnique" => false,
			"Remaining" => null,
			"Sales" => null,
			"MinimumMembershipLevel" => 0
		])); 
	} else {
		die("{}");
	}

	

?>