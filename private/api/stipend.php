<?php
	use anorrl\utilities\TransactionUtils;

	header("Content-Type: application/json");

	

	if(SESSION) {
		$user = SESSION->user;
		if(!$user->isBanned() && $user->pendingStipend()) {
			TransactionUtils::StipendCheckToUser($user->id);
			die(json_encode(["error" => false, "reason" => "Successfully given!"]));
		} else {
			die(json_encode(["error" => true, "reason" => "Haven't you already gotten this?"]));
		}
	} else {
		die(json_encode(["error" => true, "reason" => "User not logged in."]));
	}
	
?>