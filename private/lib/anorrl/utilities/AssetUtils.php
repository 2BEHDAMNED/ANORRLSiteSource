<?php
	namespace anorrl\utilities;

	use anorrl\Asset;
	use anorrl\Database;
	use anorrl\Place;
	use anorrl\enums\AssetType;
	use anorrl\enums\CatalogFilter;
	use anorrl\utilities\UserUtils;
	
	class AssetUtils {
		
		public static function Get(AssetType $type, string $query = "", int $page = -1, int $count = -1): array {
			$user = UserUtils::RetrieveUser();
			$db = Database::singleton();

			if($user == null) 
				return [];
			
			$query_filter = "AND `public` = 1 AND `nevershow` = 0";
			if($user->isAdmin()) {
				$query_filter = "AND `nevershow` = 0";
			}
			
			$stmt_query = "%$query%";
			$stmt_type = $type->ordinal();
			
			$rows = [];

			if($page == -1 || $count == -1) {
				$rows = $db->run(
					"SELECT `id`,`type` FROM `assets` WHERE `name` LIKE :search AND `type` = :type $query_filter",
					[
						":search" => $stmt_query,
						":type" => $stmt_type
					]
				)->fetchAll(\PDO::FETCH_OBJ);

				// show all
			} else {
				// pagify

				$rows = $db->run(
					"SELECT `id`,`type` FROM `assets` WHERE `name` LIKE :search AND `type` = :type $query_filter LIMIT :page, :size",
					[
						":search" => $stmt_query,
						":type" => $stmt_type,
						":page" => (($page-1)*$count),
						":size" => $count
					]
				)->fetchAll(\PDO::FETCH_OBJ);
			}

			$result_array = [];
			
			foreach($rows as $row) {
				if($type == AssetType::PLACE->ordinal()) {
					$asset = Place::FromID($row->id);
				} else {
					$asset = Asset::FromID($row->id);
				}

				if($user->isAdmin() || !$asset->notcatalogueable && $asset->public) {
					$result_array[] = $asset;
				}
			}

			return $result_array;
		}
		
		public static function GetFiltered(CatalogFilter $filter, AssetType $type, string $query, int $page = -1, int $count = -1) {

			if($type != AssetType::PLACE && 
				($filter == CatalogFilter::MostPopular || $filter == CatalogFilter::MostVisited)) {
				$filter = CatalogFilter::RecentlyUploaded;
			}

			include $_SERVER["DOCUMENT_ROOT"]."/private/connection.php";

			$user = UserUtils::RetrieveUser();
			if($user == null) 
				return [];
			
			$query_filter = "AND `public` = 1 AND `nevershow` = 0";
			if($user->isAdmin()) {
				$query_filter = "AND `nevershow` = 0";
			}

			$base_sql_query = "SELECT `id` FROM `assets` WHERE `name` LIKE ? AND `type` = ? $query_filter";
			if($type == AssetType::PLACE) {
				$base_sql_query = "SELECT places.id FROM `places`, `assets` WHERE assets.id = places.id AND `name` LIKE ? AND `type` = ? $query_filter ".($_SESSION['ANORRL$Games$OriginalOnly'] ? " AND `original` = 1 " : "");
			}
			
			$sql_filter = $filter->getSQL();
			
			$stmt_query = "%$query%";
			$stmt_type = $type->ordinal();

			if($page == -1 || $count == -1) {
				$stmt_getuser = $con->prepare("$base_sql_query $sql_filter");
				$stmt_getuser->bind_param('si', $stmt_query, $stmt_type);
				$stmt_getuser->execute();
			} else {
				$stmt_page = (($page-1)*$count);
				
				$stmt_getuser = $con->prepare("$base_sql_query $sql_filter LIMIT ?, ?");
				$stmt_getuser->bind_param('siii', $stmt_query, $stmt_type, $stmt_page, $count);
				$stmt_getuser->execute();
			}

			$result = $stmt_getuser->get_result();

			$result_array = [];

			if($result->num_rows != 0) {
				while($row = $result->fetch_assoc()) {
					if($type == AssetType::PLACE) {
						$asset = Place::FromID($row['id']);
					} else {
						$asset = Asset::FromID($row['id']);
					}

					if($user->isAdmin() || !$asset->notcatalogueable && $asset->public) {
						$result_array[] = $asset;
					}
				}
				return $result_array;
			}

			return $result_array;
		}

		public static function GetFilteredCount(CatalogFilter $filter, AssetType $type, string $query, int $page = -1, int $count = -1) {

			if($type != AssetType::PLACE && 
				($filter == CatalogFilter::MostPopular || $filter == CatalogFilter::MostVisited)) {
				$filter = CatalogFilter::RecentlyUploaded;
			}

			include $_SERVER["DOCUMENT_ROOT"]."/private/connection.php";
			
			$user = UserUtils::RetrieveUser();
			if($user == null) 
				return 0;
			
			$query_filter = "AND `public` = 1 AND `nevershow` = 0";
			if($user->isAdmin()) {
				$query_filter = "AND `nevershow` = 0";
			}

			$base_sql_query = "SELECT COUNT(`id`) FROM `assets` WHERE `name` LIKE ? AND `type` = ? $query_filter";
			if($type == AssetType::PLACE) {
				$base_sql_query = "SELECT COUNT(`places`.`id`) FROM `places`, `assets` WHERE assets.id = places.id AND `name` LIKE ? AND `type` = ? $query_filter ".($_SESSION['ANORRL$Games$OriginalOnly'] ? " AND `original` = 1 " : "");
			}
			
			$sql_filter = $filter->getSQL();
			
			$stmt_query = "%$query%";
			$stmt_type = $type->ordinal();

			if($page == -1 || $count == -1) {
				$stmt_getuser = $con->prepare("$base_sql_query $sql_filter");
				$stmt_getuser->bind_param('si', $stmt_query, $stmt_type);
				$stmt_getuser->execute();
			} else {
				$stmt_page = (($page-1)*$count);
				
				$stmt_getuser = $con->prepare("$base_sql_query $sql_filter LIMIT ?, ?");
				$stmt_getuser->bind_param('siii', $stmt_query, $stmt_type, $stmt_page, $count);
				$stmt_getuser->execute();
			}

			$result = $stmt_getuser->get_result();

			$row = $result->fetch_assoc();

			if($row == null) {
				return -1;	
			}
			
			if($type == AssetType::PLACE) {
				return $row['COUNT(`places`.`id`)'];
			}
			
			return $row['COUNT(`id`)'];
		}
	}
?>
