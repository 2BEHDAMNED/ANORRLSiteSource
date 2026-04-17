<?php 
	namespace anorrl\utilities;

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


		public static function RenderClothing(int $id = 0) {
			return self::DoRender(
				"avatar",
				[
					"UserId" => $id,
					"IsHeadshot" => false,
					"IsClothing" => true
				]
			);
		}

		public static function RenderUser(int $id = 0, bool $headshot = false) {
			return User::Exists($id) ? self::DoRender(
				"avatar",
				[
					"UserId" => $id,
					"IsHeadshot" => $headshot,
					"IsClothing" => false
				]
			) : null;
		}

		public static function RenderMesh(int $id = 0) {
			return self::DoRender("mesh", ["MeshId" => $id]);
		}

		public static function RenderPlace(int $id = 0) {
			return self::DoRender("place", ["PlaceId" => $id]);
		}

		public static function RenderModel(int $id = 0) {
			return self::DoRender("model", ["AssetId" => $id]);
		}
	}
?>