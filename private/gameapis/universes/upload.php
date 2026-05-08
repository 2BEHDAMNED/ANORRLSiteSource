<?php
	use anorrl\enums\AssetType;
	use anorrl\utilities\ClientDetector;
	use anorrl\utilities\AssetUploader;

	set_header("Content-Type", "application/json");

	if(!SESSION)
		die(http_response_code(500));

	$user = SESSION->user;

	//assetType=13&name=Images%2Fballhhhhhhhh&description=madeinstudio

	if(
		isset($_POST['assetType']) &&
		isset($_POST['name']) &&
		isset($_POST['description']) &&
		ClientDetector::IsAClient()
	) {
		if(intval($_POST['assetType']) != 13 || !str_ends_with($_POST['name'], "Images%2F"))
			die(json_encode(["Success" => false, "Message" => "Any other asset type id has not been implemented yet sorry!"]));

		$contents = file_get_contents("php://input");
		$image = imagecreatefromstring($contents);

		if(!($image instanceof GdImage))
			die(json_encode([
				"Success" => false,
				"Message" => "That was not an image pal."
			]));

		$name = urldecode($_POST['name']);


		$result = AssetUploader::UploadAsset($contents, AssetType::index(intval($_POST['assetType'])), $name, "madeinstudio", false, false, true);

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