<?php

	namespace anorrl;

	use anorrl\Asset;
	use anorrl\Database;
	use anorrl\enums\AssetType;
	use anorrl\enums\ANORRLBadge;
	use anorrl\utilities\AssetUtils;
	use anorrl\utilities\Arbiter;
	use anorrl\GameServer;
	use anorrl\Universe;

	class Place extends Asset {
		public int  $server_size;
		public int  $visit_count;
		public int  $current_playing_count;
		public bool $copylocked;
		public bool $teamcreate;

		public static function UpdatePlaceStats(int $placeID) {
			$place = Place::FromID($placeID);

			if($place != null) {
				$fetch_servers = Database::singleton()->run(
					"SELECT * FROM `active_servers` WHERE `placeid` = :placeid AND `teamcreate` = 0;",
					[ ":placeid" => $place->id ]
				)->fetchAll(\PDO::FETCH_OBJ);

				$concurrentplayers = 0;

				foreach($fetch_servers as $server_row) {
					$fetch_players = Database::singleton()->run(
						"SELECT COUNT(`id`) FROM `active_players` WHERE `serverid` = :serverid AND `status` = 1;",
						[ ":serverid" => $server_row->id ]
					)->fetch(\PDO::FETCH_ASSOC);

					$concurrentplayers += $fetch_players['COUNT(`id`)'];
				}

				$fetch_servers = Database::singleton()->run(
					"UPDATE `places` SET `currently_playing_count` = :playerscount WHERE `id` = :placeid",
					[
						":placeid" => $place->id,
						":playerscount" => $concurrentplayers
					]
				);
			}
		}

		public static function UpdateAllPlaces() {
			foreach(AssetUtils::Get(AssetType::PLACE) as $place) {
				if($place instanceof Place) {
					$visits = $place->visit_count;
					
					if($visits > 100 && !$place->creator->hasProfileBadgeOf(ANORRLBadge::HOMESTEAD)) {
						$place->creator->giveProfileBadge(ANORRLBadge::HOMESTEAD);
					}

					if($visits > 1000 && !$place->creator->hasProfileBadgeOf(ANORRLBadge::BRICKSMITH)) {
						$place->creator->giveProfileBadge(ANORRLBadge::BRICKSMITH);
					}

					self::UpdatePlaceStats($place->id);
				}
				
			}
		}

		public static function FromID(int $id): Place|null {
			$row = Database::singleton()->run(
				"SELECT * FROM `places` WHERE `id` = :id",
				[
					":id" => $id
				]
			)->fetch(\PDO::FETCH_OBJ);

			return $row ? new self($row) : null;
		}

		private function __construct(object $rowdata) {
			parent::__construct($rowdata->id);

			$this->server_size = $rowdata->serversize;
			$this->visit_count = $rowdata->visit_count;
			$this->current_playing_count = $rowdata->currently_playing_count;
			$this->teamcreate = $rowdata->teamcreate_enabled;

			if(!$this->universe)
				$this->universe = Universe::Create($this);
		}

		function updateVisitCount() {
			$db = Database::singleton();

			$visits = $db->run(
				'SELECT * FROM `visits` WHERE `place` = :id',
				[":id" => $this->id]
			)->rowCount();

			$db->run(
				'UPDATE `places` SET `visit_count` = :visits WHERE `id` = :id',
				[
					":visits" => $visits,
					":id" => $this->id
				]
			);

			$this->visit_count = $visits;

			if($this->visit_count > 100) {
				$this->creator->giveProfileBadge(ANORRLBadge::HOMESTEAD);
			}

			if($this->visit_count > 1000) {
				$this->creator->giveProfileBadge(ANORRLBadge::BRICKSMITH);
			}
		}

		function getServers(bool $teamcreate = false): array {
			$rows = Database::singleton()->run(
				"SELECT * FROM `active_servers` WHERE `placeid` = :placeid AND `teamcreate` = :teamcreate",
				[
					":placeid" => $this->id,
					":teamcreate" => $teamcreate
				]
			)->fetchAll(\PDO::FETCH_OBJ);

			$result = [];

			foreach($rows as $row) {
				$server = new GameServer($row);

				if($server->active())
					$result[] = $server;
			}

			return $result;
		}

		function isEditable(User $user): bool {
			return 
				$this->isOwner($user) ||
				// move this to like Universe::hasAccess or something
				!$this->universe->copylocked ||
				($this->universe->teamcreate && $this->isCloudEditor($user));
		}

		function anyActiveServers(bool $teamcreate = false): bool {
			return Database::singleton()->run(
				"SELECT * FROM `active_servers` WHERE `placeid` = :placeid AND `playercount` != `maxcount` AND `teamcreate` = :teamcreate",
				[
					":placeid" => $this->id,
					":teamcreate" => $teamcreate
				]
			)->rowCount() != 0;
		}

		function getAnActiveServer(User $user, bool $teamcreate = false): GameServer|null {
			$row = Database::singleton()->run(
				"SELECT * FROM `active_servers` WHERE `placeid` = :placeid AND `playercount` < `maxcount` AND `teamcreate` = :teamcreate",
				[
					":placeid" => $this->id,
					":teamcreate" => $teamcreate
				]
			)->fetch(\PDO::FETCH_OBJ);

			if(!$row)
				return null;

			$gameserver = new GameServer($row);

			return $gameserver->active() && !$gameserver->isPlayerInServer($user) ? $gameserver : null;
		}
		

		function getBadges(): array {
			return [];
		}

		function update(bool $copylocked, int $server_size, bool $original, bool $gears) {
			Database::singleton()->run(
				"UPDATE `places` SET `copylocked` = :copylocked, `serversize` = :serversize, `original` = :original, `gears_enabled` = :gears WHERE `id` = :placeid",
				[
					":copylocked" => $copylocked,
					":serversize" => $server_size,
					":original" => $original,
					":gears" => $gears,
					":placeid" => $this->id
				]
			);
		}
	}

?>
