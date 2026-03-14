<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	header("Content-Type: application/json");
	if(isset($_GET['userId'])) {
		$user = User::FromID(intval($_GET['userId']));

		if($user != null) {
			die(json_encode(
				[
					"success" => true,
					"count" => $user->GetFriendsCount()
				]
			));
		} else {
			$user = UserUtils::RetrieveUser();

			if($user != null) {
				die(json_encode(
					[
						"success" => true,
						"count" => $user->GetFriendsCount()
					]
				));
			} 
		}
	} else {
		$user = UserUtils::RetrieveUser();

		if($user != null) {
			die(json_encode(
				[
					"success" => true,
					"count" => $user->GetFriendsCount()
				]
			));
		} 
	}

	die(json_encode(
		[
			"success" => false
		]
	));
?>