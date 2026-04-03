<?php
	header("Content-Type: application/json");

	//?maxsets=10&rqtype=getrobloxsets

	if(isset($_GET['rqtype'])) {
		$type = $_GET['rqtype'];

		if($type == "getrobloxsets") {
			die(include "get-roblox-sets.php");
		}
		else if($type == "getsetinfo") {
			die(include "get-set-info.php");
		}
		else if($type == "getsetitems") {
			die(include "get-set-items.php");
		}
		else if($type == "getmydecals") {
			die(include "get-my-decals.php");
		}
		else if($type == "getmymodels") {
			die(include "get-my-models.php");
		}
	}

	die(json_encode(["TotalNumAssetsInSet" => 0]));
?>