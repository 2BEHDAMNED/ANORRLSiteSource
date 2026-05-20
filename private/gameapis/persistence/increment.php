<?php
	set_content_type(ARLTYPEJSON);
	http_response_code(501);
	exit(json_encode(["error"=>"Not Implemented"]));
?>
