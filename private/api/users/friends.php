
<?php

	use anorrl\User;

	header('Content-type: application/json');
	$user = User::FromID($id);

	if($user != null) {
		$friends = $user->GetFriends();
		$result = [];
		foreach($friends as $friend) {
			$result[] = [
				"Id" => $friend->id,
				"Username" => $friend->id
			];
		}

		die(json_encode($result));
	}

	echo "{}";

?>
