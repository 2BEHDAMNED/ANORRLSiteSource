<?php
	use anorrl\Place;
	use anorrl\enums\AssetType;
	
	//?AssetTypeId=21&TargetPlaceId=525
	
	if(isset($_GET['AssetTypeId']) && isset($_GET['TargetPlaceId'])) {
		$place = Place::FromID(intval($_GET['TargetPlaceId']));
		if(intval($_GET['AssetTypeId']) == AssetType::BADGE->ordinal() && $place) {
			die(set_header("Location", "/create/{$place->id}/badge"));
		}
	}
?>