<?php
	use anorrl\enums\AssetType;
	use anorrl\utilities\ClientDetector;
	use anorrl\utilities\AssetUploader;

	set_content_type(ARLTYPEJSON);

	if(!SESSION)
		die(http_response_code(500));

	$user = SESSION->user;

	//assetType=13&name=Images%2Fballhhhhhhhh&description=madeinstudio

	if(
		isset($_GET['assetTypeId']) &&
		isset($_GET['name']) &&
		isset($_GET['description']) &&
		ClientDetector::IsAClient()
	) {
		if(intval($_GET['assetTypeId']) != 13 || !str_starts_with($_GET['name'], "Images"))
			die(json_encode(["Success" => false, "Message" => "Any other asset type id has not been implemented yet sorry!"]));

		$contents = file_get_contents("php://input");
		$image = imagecreatefromstring($contents);

		if(!($image instanceof GdImage))
			die(json_encode([
				"Success" => false,
				"Message" => "That was not an image pal."
			]));

		$name = urldecode($_GET['name']);


		$result = AssetUploader::UploadAsset($contents, AssetType::index(intval($_GET['assetTypeId'])), $name, "madeinstudio", false, false, true);

		if(!$result['error']) {
			die(json_encode([
				"Success" => true,
				"BackingAssetId" => $result['id']
			]));
		}
		else {
			die(json_encode([
				"Success" => false,
				"Message" => $result['reason']
			]));
		}
		
	}

	die(json_encode([ "Success" => false ]));
?>
