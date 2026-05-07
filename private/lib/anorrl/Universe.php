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
		public bool $original;
		public bool $teamcreate;

		public static function Create(Place $place, bool $public = true, bool $original = true): self|null {
			if(!$place->universe) {
				Database::singleton()->run(
					"INSERT INTO `universes`(`starting_place`, `creator`, `public`, `original`) VALUES (:placeid, :creator, :public, :original)",
					[
						":placeid" => $place->id,
						":creator" => $place->creator->id,
						":public" => $public,
						":original" => $original
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

		public static function FromID(?int $id): self|null {
			if(!is_int($id))
				return null;

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
			$this->public = $data->public;
			$this->original = $data->original;
			$this->teamcreate = $data->teamcreate;
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
				"SELECT `id` FROM `assets` WHERE `universe` = :id AND `type` != :placetype AND `type` = :type",
				[ ":id" => $this->id, ":placetype" => AssetType::PLACE->ordinal(), ":type" => $type->ordinal() ]
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
			Database::singleton()->run("UPDATE `universes` SET `teamcreate` = 1 WHERE `id` = :id", [ ":id" => $this->id ]);

			if(!$this->isCloudEditor($this->creator)) {
				$this->addCloudEditor($this->creator);
			}
		}

		function disableTeamCreate() {

			$db = Database::singleton();

			$db->run("UPDATE `universes` SET `teamcreate` = 0 WHERE `id` = :id", [":id" => $this->id]);

			if($this->teamcreate) {
				$db->run(
					"DELETE FROM `cloudeditors` WHERE `userid` != :creator AND `universe` = :universe;",
					[
						":creator" => $this->creator->id,
						":universe" => $this->id
					]
				);

				foreach($this->getAllPlaces() as $place) {
					$teamcreate_servers = $place->getServers(true);

					foreach($teamcreate_servers as $server) {
						$server->destroy();
					}
				}
			}
		}

		function isCloudEditor(User $user) {
			if($this->teamcreate) {
				return Database::singleton()->run(
					"SELECT `id` FROM `cloudeditors` WHERE `userid` = :uid AND `universe` = :id",
					[
						":uid" => $user->id,
						":id" => $this->id
					]
				)->rowCount() != 0;
			}
			return false;
		}

		function addCloudEditor(User $user) {
			if(!$this->isCloudEditor($user) && !$user->isBanned()) {
				return Database::singleton()->run(
					"INSERT INTO `cloudeditors`(`userid`, `universe`) VALUES (:uid, :id)",
					[
						":uid" => $user->id,
						":id" => $this->id
					]
				);
			}	
		}

		function removeCloudEditor(User $user) {
			if($this->isCloudEditor($user) && $user->id != $this->creator->id) {
				return Database::singleton()->run(
					"DELETE FROM `cloudeditors` WHERE `userid` = :uid AND `universe` = :id",
					[
						":uid" => $user->id,
						":id" => $this->id
					]
				);
			}	
		}

		function getCloudEditors() {
			if($this->teamcreate) {
				$rows = Database::singleton()->run(
					"SELECT `userid` FROM `cloudeditors` WHERE `universe` = :id",
					[ ":id" => $this->id ]
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

		function isOwner(User $user) {
			return $user->id == $this->creator->id || $user->isAdmin();
		}

		function hasAccess(User|null $user = null) {
			if(!$user)
				return false;

			return $this->isOwner($user) || $this->teamcreate && $this->isCloudEditor($user);
		}

		function getBadges(): array {
			return [];
		}
	}
?>