<?php
	use anorrl\Universe;
	use anorrl\utilities\AssetUploader;
	
	if(!SESSION || !isset($_GET['universeId']))
		die(http_response_code(403));

	$universe = Universe::FromID(intval($_GET['universeId']));

	if(!$universe)
		die(http_response_code(503));

	if(!$universe->isOwner(SESSION->user))
		die(http_response_code(403));

	$result = AssetUploader::CreateSubPlace($universe);

	if($result['error']) {
		die(http_response_code(500));
	}
?>
