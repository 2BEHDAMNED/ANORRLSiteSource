<?php

	namespace anorrl;

	use anorrl\Asset;
	use anorrl\Database;
	use anorrl\User;

	class Badge extends Asset {
		
		public static function FromID(?int $id): Badge|null {
			if(!is_int($id))
				return null;
			
			$row = Database::singleton()->run(
				"SELECT * FROM `assets` WHERE `id` = :id LIMIT 1",
				[ ":id" => $id ]
			)->fetchObject();

			return $row ? new self($row) : null;
		}

		function __construct(int|object $rowdata) {
			parent::__construct($rowdata);
		}

		function awardTo(User $user) {
			if($user->isBanned() || $user->owns($this)) {
				return false;
			}

			return !$this->purchase($user)["error"];
		}

		// Stubs \\

		function getRarity() {
			return 0.0;
		}

		function getWonYesterdayTimes() {
			return 0;
		}

		function getWonEverTimes() {
			return 0;
		}

	}

?>
