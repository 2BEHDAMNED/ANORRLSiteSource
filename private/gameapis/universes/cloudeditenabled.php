<?php
	use anorrl\Universe;

	header("Content-Type: application/json");

	// dont cache this shit!
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	$universe = Universe::FromID(intval($universeId));


	if($universe != null) {
		echo json_encode([
			"enabled" => $universe->teamcreate
		]);
	} else {
		echo "{}";
	}
?>