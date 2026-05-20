<?php
	use anorrl\utilities\Thumbnail;
	
	set_content_type(ARLTYPEPLAIN);

	if(!isset($hash))
		die(http_response_code(500));

	$data = Thumbnail::Get3DMtl($hash, false);

	if(!$data)
		die(http_response_code(500));

	exit($data);
?>