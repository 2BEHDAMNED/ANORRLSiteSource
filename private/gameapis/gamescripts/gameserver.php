<?php
	use anorrl\Script;

	header("Content-Type: text/plain");

	die(new Script("gameserver")->sign());
?>
