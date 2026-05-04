<?php
	namespace anorrl;

	use anorrl\Place;
	use anorrl\User;
	use anorrl\utilities\Arbiter;
	use anorrl\GameServer;
	use anorrl\Database;
	use anorrl\enums\AssetType;

	class Universe {

		public int $id;
		public Place $starting_place;
		public User $creator;
		public bool $public;
		public bool $gears_enabled;
		public bool $original;
		public bool $teamcreate;

		public static function Create(Place $place, bool $public = true, bool $original = true): self|null {
			if(!$place->universe) {
				Database::singleton()->run(
					"INSERT INTO `universes`(`starting_place`, `creator`) VALUES (:placeid, :creator)",
					[
						":placeid" => $place->id,
						":creator" => $place->creator->id
					]
				);

				$id = intval(Database::singleton()->lastInsertId());
				if($id == 0)
					return null;

				Database::singleton()->run(
					"UPDATE `assets` SET `universe`= :uid WHERE `id` = :id",
					[
						":uid" => $id,
						":id" => $place->id
					]
				);

				return self::FromID($id);
			}

			return null;
		}

		public static function FromID(int $id): self|null {
			$row = Database::singleton()->run(
				"SELECT * FROM `universes` WHERE `id` = :id",
				[ ":id" => $id ]
			)->fetchObject();

			return $row ? new self($row) : null;
		}

		private function __construct($data) {
			$this->id = $data->id;
			$this->starting_place = Place::FromID($data->starting_place);
			$this->creator = User::FromID($data->creator);
		}

		function getAllPlaces() {
			$rows = Database::singleton()->run(
				"SELECT `id` FROM `assets` WHERE `universe` = :id AND `type` = :type",
				[ ":id" => $this->id, ":type" => AssetType::PLACE->ordinal() ]
			)->fetchAll(\PDO::FETCH_OBJ);

			$places = [];

			foreach($rows as $row) {
				$place = Place::FromID($row->id);

				if($place)
					$places[] = $place;
			}

			return $places;
		}

		function getDeveloperProducts(AssetType $type) {
			$rows = Database::singleton()->run(
				"SELECT `id` FROM `assets` WHERE `universe` = :id AND `type` != :type AND `type` = :lookingtype",
				[ ":id" => $this->id, ":type" => AssetType::PLACE->ordinal(), ":lookingtype" => $type->ordinal() ]
			)->fetchAll(\PDO::FETCH_OBJ);

			$places = [];

			foreach($rows as $row) {
				$place = Place::FromID($row->id);

				if($place)
					$places[] = $place;
			}

			return $places;
		}

		function enableTeamCreate() {
			Database::singleton()->run("UPDATE `places` SET `teamcreate_enabled` = 1 WHERE `id` = :id", [ ":id" => $this->id ]);

			if(!$this->isCloudEditor($this->creator)) {
				$this->addCloudEditor($this->creator);
			}
		}

		function disableTeamCreate() {

			$db = Database::singleton();

			$db->run("UPDATE `places` SET `teamcreate_enabled` = 0 WHERE `id` = :placeid", [":placeid" => $this->id]);

			if($this->teamcreate) {
				$db->run(
					"DELETE FROM `cloudeditors` WHERE `userid` != :creator AND `placeid` = :placeid;",
					[
						":creator" => $this->creator->id,
						":placeid" => $this->id
					]
				);


				/*$teamcreate_servers = $this->getServers(true);

				foreach($teamcreate_servers as $server) {
					$server->destroy();
				}*/
			}
		}

		function isCloudEditor(User $user) {
			if($this->teamcreate) {
				return Database::singleton()->run(
					"SELECT `id` FROM `cloudeditors` WHERE `userid` = :uid AND `placeid` = :pid",
					[
						":uid" => $user->id,
						":pid" => $this->id
					]
				)->rowCount() != 0;
			}
			return false;
		}

		function addCloudEditor(User $user) {
			if(!$this->isCloudEditor($user) && !$user->isBanned()) {
				return Database::singleton()->run(
					"INSERT INTO `cloudeditors`(`userid`, `placeid`) VALUES (:uid, :pid)",
					[
						":uid" => $user->id,
						":pid" => $this->id
					]
				);
			}	
		}

		function removeCloudEditor(User $user) {
			if($this->isCloudEditor($user) && $user->id != $this->creator->id) {
				return Database::singleton()->run(
					"DELETE FROM `cloudeditors` WHERE `userid` = :uid AND `placeid` = :pid",
					[
						":uid" => $user->id,
						":pid" => $this->id
					]
				);
			}	
		}

		function getCloudEditors() {
			if($this->teamcreate) {
				$rows = Database::singleton()->run(
					"SELECT `userid` FROM `cloudeditors` WHERE `placeid` = :place",
					[ ":place" => $this->id ]
				)->fetchAll(\PDO::FETCH_OBJ);
				
				$editors = [];

				foreach($rows as $row) {
					$user = User::FromID($row->userid);

					if(!$user)
						continue;

					if(!$user->isBanned())
						$editors[] = $user;
					else
						$this->removeCloudEditor($user);
				}

				return $editors;
			}
			return [];
		}
	}
?>