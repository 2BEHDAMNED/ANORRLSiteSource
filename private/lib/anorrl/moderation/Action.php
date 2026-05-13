<?php
	// just had an idea for this, moderating.

	namespace anorrl\moderation;

	use anorrl\Asset;
	use anorrl\Comment;
	use anorrl\Place;
	use anorrl\User;
	use anorrl\moderation\ActionType;

	class Action {
		
		public int $id;
		public ActionType $type;
		public User $user;
		public string $message;
		public \DateTime $time_taken;

		public static function Track(User $user, ActionType $type, $data) {
			// track and log
		}
		
		public static function FromID(?int $id): self|null {
			return null;
		}

	}
?>