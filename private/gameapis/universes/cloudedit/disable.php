<?php
	use anorrl\Universe;
	
	set_content_type(ARLTYPEJSON);

	$universe = Universe::FromID(intval($universeId));
	$user = SESSION->user;

	if($universe && $user && $universe->isOwner($user)) {
		$universe->disableTeamCreate();
		echo "{}";
	}
?>
