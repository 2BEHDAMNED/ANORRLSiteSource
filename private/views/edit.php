<?php
	/**
	 *  NO.
	 *  This is not where you create places, rather this is to create ASSETS (like badges) **FOR** places.
	 */

	use anorrl\Place;
	use anorrl\utilities\ClientDetector;

	if(!ClientDetector::IsAClient() && false) {
		require "edit/normal.php";
	}
	else {
		require "edit/studio.php";
	}

?>
