<?php 
	use anorrl\utilities\TransactionUtils;
	
	header("Content-Type: application/json");

	// rewrite this shit

	if(SESSION) {
		$user = SESSION->user;
		if(!$user->IsBanned() && isset($_POST['asset_id']) && isset($_POST['typatransaction'])) {
			$type = strtolower(trim($_POST['typatransaction']));
			$result = TransactionUtils::BuyItem($_POST['asset_id']);
			if($result != "yay") {
				echo "{ \"error\" : true, \"message\":\"$result\"}";
			} else {
				echo "{ \"error\" : false, \"message\":\"Success!\"}";
			}
		} else {
			echo "{ \"error\" : true, \"message\":\"Request failed!\"}";
		}

		
	} else {
		echo "{ \"error\" : true, \"message\":\"User is not logged in.\"}";
	}

?>
