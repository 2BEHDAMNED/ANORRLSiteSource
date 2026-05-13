<?php
	use anorrl\utilities\ClientDetector;
	use anorrl\Badge;
	use anorrl\User;
	use anorrl\Place;
	
	if(
		isset($_GET['UserID']) && 
		isset($_GET['BadgeID']) && 
		isset($_GET['PlaceID']) &&
		ClientDetector::HasAccess()
	) {
		$user = User::FromID(intval($_GET['UserID']));
		$badge = Badge::FromID(intval($_GET['BadgeID']));
		$place = Place::FromID(intval($_GET['PlaceID']));

		if($user && $badge && $place) {
			if($badge->relatedasset->id == $place->id) {
				if($badge->awardTo($user)) {
					die("You won {$badge->creator->name}'s '$badge->name' award!");
				}
			}
		}
	}
?>