<?php
	header("Content-Type: application/json");

	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/assetutils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";


	$user = UserUtils::RetrieveUser();

	if($user != null) {

		
		$type = AssetType::HAT->ordinal();
		if(isset($_GET['c'])) {
			$type = intval($_GET['c']);
		}
		$page = 1;
		if(isset($_GET['p'])) {
			$page = intval($_GET['p']);
		}

		if($page < 1) {
			die(header("Location: /api/stuff?c=$type&p=1"));
		}

		$showcreatoronly = false;

		if(isset($_GET['showcreatoronly'])) {
			$showcreatoronly = true;
		}

		$total_pages = floor(count($user->GetAllOwnedAssetsOfType(AssetType::index($type), true, $showcreatoronly))/12)+1;

		if($total_pages < $page) {
			die(header("Location: /api/stuff?c=$type&p=1"));
		}

		$assets = $user->GetAllOwnedAssetsOfTypePaged(AssetType::index($type), $page, 12, $showcreatoronly);

		$assets_raw = [];

		if(count($assets) != 0) {
			foreach($assets as $asset) {
				if($asset instanceof Asset) {
					array_push($assets_raw, [
						"id" => $asset->id,
						"name" => $asset->name,
						"creator" => [
							"id" => $asset->creator->id,
							"name" => $asset->creator->name
						]
					]);
				}
			}
		}
		
		die(json_encode(["assets" => $assets_raw, "page" => $page, "total_pages" => $total_pages]));
	} else {
		die(json_encode(["error" => true, "reason" => "User not logged in."]));
	}
	
?>