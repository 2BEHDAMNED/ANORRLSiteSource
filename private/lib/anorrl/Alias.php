<?php

	namespace anorrl;

	use anorrl\Asset;
	use anorrl\Database;
	use anorrl\Universe;

	class Alias {

		public int $id;
		public string $name;
		public Asset $asset;
		public Universe $universe;

		public static function Create(Universe $universe, Asset $asset, string $name) {
			$alias = self::FromAsset($universe, $asset);

			if(!$alias) {
				// create new alias
				Database::singleton()->run(
					"INSERT INTO `aliases`(`name`, `asset`, `universe`) VALUES (:name, :asset, :universe)",
					[
						":name" => $name,
						":asset" => $asset->id,
						":universe" => $universe->id
					]
				);

				$id = intval(Database::singleton()->lastInsertId());
				if($id == 0)
					return null;
				
				//$asset->setUniverse($universe); // maybe ?

				return self::FromID($id);
			}

			// return null because like we already HAVE an alias on that universe, why do more?

			return null; //$alias;
		}

		public static function FromID(?int $id) {
			if(!is_int($id))
				return null;

			$row = Database::singleton()->run(
				"SELECT * FROM `aliases` WHERE `id` = :id",
				[ ":id" => $id ]
			)->fetchObject();

			return $row ? new self($row) : null;
		}

		public static function FromName(Universe|int $universe, string $name) {
			$row = Database::singleton()->run(
				"SELECT `id` FROM `aliases` WHERE `universe` = :universe AND `name` LIKE :name",
				[
					":universe" => is_int($universe) ? $universe : $universe->id,
					":name" => $name
				]
			)->fetchObject();

			return $row ? self::FromID($row->id) : null;
		}

		public static function FromAsset(Universe $universe, Asset $asset) {
			$row = Database::singleton()->run(
				"SELECT `id` FROM `aliases` WHERE `universe` = :universe AND `asset` = :asset",
				[
					":universe" => $universe->id,
					":asset" => $asset->id
				]
			)->fetchObject();

			return $row ? self::FromID($row->id) : null;
		}

		private function __construct(object $rowdata) {
			$this->id = $rowdata->id;
			$this->name = $rowdata->name;
			$this->asset = Asset::FromID($rowdata->asset);
			$this->universe = Universe::FromID($rowdata->universe);
		}

		function renameTo(string $name) {
			if(strcmp($name, $this->name) == 0)
				return;

			Database::singleton()->run(
				"UPDATE `aliases` SET `name`= :name WHERE `id` = :id",
				[
					":id" => $this->id,
					":name" => $name
				]
			);
		}
	}
?>