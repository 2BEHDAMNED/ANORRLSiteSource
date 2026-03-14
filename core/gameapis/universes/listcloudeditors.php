<?php 
	require_once $_SERVER['DOCUMENT_ROOT'] . "/core/utilities/userutils.php";
	require_once $_SERVER['DOCUMENT_ROOT'] . "/core/utilities/assetutils.php";
	header("Content-Type: application/json");
	// dont cache this shit!
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	if(isset($_GET['universeId'])) {
		$place = Place::FromID(intval($_GET['universeId']));

		if($place != null && $place->teamcreate_enabled) {
			$editorusers = $place->GetCloudEditors();

			$editors = [];

			foreach($editorusers as $user) {
				if($user instanceof User) {
					if(!$user->IsBanned()) {
						array_push($editors, [
							"userId" => $user->id,
							"isAdmin" => $user->id == $place->creator->id
						]);
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