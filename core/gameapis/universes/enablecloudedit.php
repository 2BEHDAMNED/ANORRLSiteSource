<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/assetutils.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/utilities/userutils.php";
	header("Content-Type: application/json");

	$place_id = intval($_GET['universeId']);

	$place = Place::FromID($place_id);
	$user = UserUtils::RetrieveUser();

	if($place != null && $user != null && ($user->id == $place->creator->id || $user->IsAdmin())) {
		$place->EnableTeamCreate();
	}
?>