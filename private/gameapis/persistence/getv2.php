<?php
	// getV2?placeId=331&type=standard&scope=global

	set_content_type(ARLTYPEJSON);
	http_response_code(501);
	exit(json_encode(["error"=>"Not Implemented"]));
?>
