<?php

	use anorrl\Alias;
	use anorrl\Universe;

	if(!SESSION || !isset($_GET['universeId']) || !isset($_GET['name']))
		die(http_response_code(403));

	$universe = Universe::FromID(intval($_GET['universeId']));

	if(!$universe)
		die(http_response_code(503));

	if(!$universe->hasAccess(SESSION->user))
		die(http_response_code(503));

	if(!str_contains($_GET['name'], "/"))
		die(http_response_code(500));

	$name = $_GET['name']; //urldecode($_GET['name']);

	$alias = Alias::FromName($universe, $name);

	if(!$alias)
		die(http_response_code(500));

	$alias->delete();
?>