<?php
	use anorrl\User;
	use anorrl\Asset;

	if(!isset($_GET['userid']) && !isset($_GET['assetid']))
		die(http_response_code(500));
	
	$data = isset($_GET['userid']) ? User::FromID(intval($_GET['userid'])) : Asset::FromID(intval($_GET['assetid']));

	if(!$data)
		die(http_response_code(500));

	set_content_type(ARLTYPEJSON);

	$api = $data instanceof User ? "avatar" : "asset";

	echo json_encode([
		"Final" => true,
		"Url" => "/thumbnail/{$api}/generate?for={$data->id}"
	])
?>