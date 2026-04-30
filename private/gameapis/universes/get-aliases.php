<?php
	header("Content-Type: application/json");
	
	echo json_encode([
		"FinalPage" => true,
		"Aliases" => [],
		"PageSize" => 50
	]);

	/*{
		"FinalPage": true,
		"Aliases": [{
			"Name": "Scripts/Init",
			"Type": 1,
			"TargetId": 718028943,
			"Asset": {
				"Id": 718028943,
				"TypeId": 5,
				"Name": "Script",
				"Description": "Script",
				"CreatorType": 1,
				"CreatorTargetId": 4719353,
				"Created": "2017-03-31T12:16:46.547",
				"Updated": "2017-08-29T08:50:09.317"
			},
			"Version": null
		}],
		"PageSize": 50
	}*/
?>
