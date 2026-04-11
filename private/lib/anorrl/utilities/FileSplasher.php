<?php

	namespace anorrl\utilities;

	class FileSplasher extends Splasher {
		
		function __construct(string $filename, bool $true_random = true, string $name = "") {
			parent::__construct(file($_SERVER["DOCUMENT_ROOT"]."/private/splashes/$filename.txt"), $true_random, $name);
		}
	}

?>