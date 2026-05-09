<?php 
	namespace anorrl\utilities;

	use anorrl\Place;
	use anorrl\User;
	use anorrl\utilities\Arbiter;

	class Renderer {

		private static function DoRender(string $type, array $data) {
			if(\CONFIG->arbiter->disabled) {
				return null;
			}
			
			$data = Arbiter::singleton()->request("$type-render", $data);

			if(!$data)
				return null;

			if(!isset($data->base64))
				return null;

			return $data->base64;
		}


		public static function RenderClothing(int $id = 0, bool $is3D = false) {
			return self::DoRender(
				"avatar",
				[
					"UserId" => $id,
					"IsHeadshot" => false,
					"IsClothing" => true,
					"Is3D" => $is3D
				]
			);
		}

		public static function RenderUser(int $id, bool $headshot = false, bool $is3D = false) {
			return User::Exists($id) ? self::DoRender(
				"avatar",
				[
					"UserId" => $id,
					"IsHeadshot" => $headshot,
					"IsClothing" => false,
					"Is3D" => $is3D
				]
			) : null;
		}

		public static function RenderMesh(int $id = 0, bool $is3D = false) {
			return self::DoRender("mesh", ["MeshId" => $id, "Is3D" => $is3D]);
		}

		public static function RenderPlace(int $id = 0) {
			$place = Place::FromID($id);
			if(!$place)
				return null;

			return self::DoRender("place", ["UniverseId" => $place->universe, "PlaceId" => $place->id]);
		}

		public static function RenderModel(int $id = 0, bool $is3D = false) {
			return self::DoRender("model", ["AssetId" => $id, "Is3D" => $is3D]);
		}
	}
?>