<?php
	//UserID=%d&BadgeID=%d
	
	use anorrl\Badge;
	use anorrl\User;

	if(
		isset($_GET['UserID']) &&
		isset($_GET['BadgeID'])
	) {
		$user = User::FromID(intval($_GET['UserID']));
		$badge = Badge::FromID(intval($_GET['BadgeID']));

		if($user && $badge) {
			die($user->owns($badge) ? "Success" : "Failure");
		}
	}

	echo "Failure"; // "Success" if they do have it
?>