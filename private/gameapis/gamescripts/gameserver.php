<?php
	use anorrl\Script;

	set_content_type(ARLTYPEPLAIN);

	die(new Script("gameserver")->sign());
?>
