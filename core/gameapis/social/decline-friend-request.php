<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header("Content-Type: application/json");

	$user = UserUtils::RetrieveUser();

	if(isset($_GET['requesterUserId']) && $user != null) {
		$toFriendUser = User::FromID(intval($_GET['requesterUserId']));

		if($toFriendUser != null) {
			$user->Unfriend($toFriendUser);

			die(json_encode(
				[
					"success" => true
				]
			));
		}
	}
	http_response_code(420);
	die(json_encode(
		[
			"success" => false
		]
	));
?>