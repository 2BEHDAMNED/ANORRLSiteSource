<?php

	namespace anorrl\enums;

	/**
	 *  Core Profile Badges.
	 */
	enum ANORRLBadge {
		case ADMINISTRATOR;
		case FORUM_MOD;
		case IMAGE_MOD;
		case HOMESTEAD;
		case BRICKSMITH;
		case FRIENDSHIP;
		case INVITER;
		case COMBAT_INITIATION;
		case WARRIOR;
		case BLOXXER;

		public function ordinal(): int {
			return match($this) {
				ANORRLBadge::ADMINISTRATOR => 1,
				ANORRLBadge::FORUM_MOD => 2,
				ANORRLBadge::IMAGE_MOD => 3,
				ANORRLBadge::HOMESTEAD => 4,
				ANORRLBadge::BRICKSMITH => 5,
				ANORRLBadge::FRIENDSHIP => 6,
				ANORRLBadge::INVITER => 7,
				ANORRLBadge::COMBAT_INITIATION => 8,
				ANORRLBadge::WARRIOR => 9,
				ANORRLBadge::BLOXXER => 10,
			};
		}

		public static function index(int $badge): ANORRLBadge {
			return match($badge) {
				1 => ANORRLBadge::ADMINISTRATOR,
				2 => ANORRLBadge::FORUM_MOD,
				3 => ANORRLBadge::IMAGE_MOD,
				4 => ANORRLBadge::HOMESTEAD,
				5 => ANORRLBadge::BRICKSMITH,
				6 => ANORRLBadge::FRIENDSHIP,
				7 => ANORRLBadge::INVITER,
				8 => ANORRLBadge::COMBAT_INITIATION,
				9 => ANORRLBadge::WARRIOR,
				10 => ANORRLBadge::BLOXXER,
			};
		}

		function name(): string {
			return match($this) {
				ANORRLBadge::ADMINISTRATOR => "Administrator",
				ANORRLBadge::FORUM_MOD => "Forum Moderator",
				ANORRLBadge::IMAGE_MOD => "Image Moderator",
				ANORRLBadge::HOMESTEAD => "Homestead",
				ANORRLBadge::BRICKSMITH => "Bricksmith",
				ANORRLBadge::FRIENDSHIP => "Friendship",
				ANORRLBadge::INVITER => "Inviter",
				ANORRLBadge::COMBAT_INITIATION => "Combat Initiation",
				ANORRLBadge::WARRIOR => "Warrior",
				ANORRLBadge::BLOXXER => "Bloxxer",
			};
		}

		function description(): string {
			return match($this) {
				ANORRLBadge::ADMINISTRATOR => "",
				ANORRLBadge::FORUM_MOD => "",
				ANORRLBadge::IMAGE_MOD => "",
				ANORRLBadge::HOMESTEAD => "",
				ANORRLBadge::BRICKSMITH => "",
				ANORRLBadge::FRIENDSHIP => "",
				ANORRLBadge::INVITER => "",
				ANORRLBadge::COMBAT_INITIATION => "",
				ANORRLBadge::WARRIOR => "",
				ANORRLBadge::BLOXXER => "",
			};
		}
	}

?>