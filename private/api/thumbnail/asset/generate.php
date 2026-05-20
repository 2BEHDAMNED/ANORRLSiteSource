<?php
	use anorrl\Asset;
	use anorrl\utilities\Thumbnail;
	
	set_content_type(ARLTYPEJSON);

	if(!isset($_GET['for']))
		die(http_response_code(500));

	$asset = Asset::FromID(intval($_GET['for']));
	
	if(!$asset)
		die(http_response_code(500));

	$generated_result = Thumbnail::Generate3D($asset);

	if(!$generated_result)
		die(http_response_code(500));

	exit(json_encode($generated_result));
?>