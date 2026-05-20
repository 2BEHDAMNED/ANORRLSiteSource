<?php
	// set?placeId=331&key=Highscore2015&&type=sorted&scope=global&target=1&valueLength=1

	set_content_type(ARLTYPEJSON);
	http_response_code(501);
	exit(json_encode(["error"=>"Not Implemented"]));
?>
