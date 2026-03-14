<?php
	header("Content-Type: application/json");

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/assetutils.php";

	//?category=FreeModels&keyword=&num=30&page=1&sort=Relevance

	$validresponse = false;

	if(
		isset($_GET['category']) &&
		isset($_GET['keyword']) &&
		isset($_GET['num']) &&
		isset($_GET['page']) &&
		isset($_GET['sort'])
	) {

		$user = null;
		$result = [];

		if(isset($_GET['creatorId'])) {
			$user = User::FromID(intval($_GET['creatorId']));
		}

		if($_GET['category'] == "FreeModels") {
			$paged_assets = AssetUtils::Get(AssetType::MODEL, $_GET['keyword'], intval($_GET['page']), intval($_GET['num']));
			$assets = AssetUtils::Get(AssetType::MODEL, $_GET['keyword']);
			$validresponse = true;
		} 
		else if($_GET['category'] == "FreeDecals") {
			$paged_assets = AssetUtils::Get(AssetType::DECAL, $_GET['keyword'], intval($_GET['page']), intval($_GET['num']));
			$assets = AssetUtils::Get(AssetType::DECAL, $_GET['keyword']);
			$validresponse = true;
		}
		else if($_GET['category'] == "FreeMeshes") {
			$paged_assets = AssetUtils::Get(AssetType::MESH, $_GET['keyword'], intval($_GET['page']), intval($_GET['num']));
			$assets = AssetUtils::Get(AssetType::MESH, $_GET['keyword']);
			$validresponse = true;
		}
		else if($_GET['category'] == "FreeAudio") {
			$paged_assets = AssetUtils::Get(AssetType::AUDIO, $_GET['keyword'], intval($_GET['page']), intval($_GET['num']));
			$assets = AssetUtils::Get(AssetType::AUDIO, $_GET['keyword']);
			$validresponse = true;
		}

		if($validresponse) {
			foreach($paged_assets as $asset) {
				if($asset instanceof Asset){
					array_push($result,
					[
						"Asset" => [
							"Id" => $asset->id,
							"Name" => $asset->name,
							"TypeId" => $asset->type->ordinal(),
							"IsEndorsed" => false
						],
						"Creator" => [
							"Id" => $asset->creator->id,
							"Name" => $asset->creator->name,
							"Type" => 1	
						],
						"Thumbnail" => [
							"Final" => true,
							"Url" => "https://arl.lambda.cam/thumbs/?id=".$asset->id."&sxy=75"
						],
						"Voting" => [
							"ShowVotes" => false
						]
					]);
				}
			}
		}
	}
	if($validresponse) {
		die(json_encode([
			"TotalResults" => count($assets),
			"Results" => $result
		]));
	} else {
		die(json_encode([
			"TotalResults" => 0,
			"Results" => []
		]));
	}
	
?>