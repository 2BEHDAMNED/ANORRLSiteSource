<?php
	use anorrl\utilities\Thumbnail;
	
	set_content_type(ARLTYPEPNG);

	if(!isset($hash) || !isset($image))
		die(http_response_code(500));


	$data = Thumbnail::Get3DTex($hash, $image);

	if(!$data)
		die(http_response_code(500));

	exit($data);
?>