<?php 
	use anorrl\Universe;
	use anorrl\User;
	
	header("Content-Type: application/json");
	// dont cache this shit!
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	if(isset($universeId)) {
		$universe = Universe::FromID(intval($universeId));

		if($universe != null && $universe->teamcreate) {
			$editorusers = $universe->getCloudEditors();

			$editors = [];

			foreach($editorusers as $user) {
				if($user instanceof anorrl\User) {
					if(!$user->isBanned()) {
						$editors[] = [
							"userId" => $user->id,
							"isAdmin" => $universe->isOwner($user)
						];
					}
				}
			}

			die(json_encode([
				"finalPage" => true,
				"users" => $editors
			]));
		}
	}

	echo "{}";
?>