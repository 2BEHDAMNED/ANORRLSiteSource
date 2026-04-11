<?php

	namespace anorrl\utilities;

	use anorrl\Asset;
	use anorrl\Database;
	use anorrl\User;
	use anorrl\enums\TransactionType;


	class TransactionUtils {
		private static function getRandomString($length = 15): string {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randomString = '';
			
			for ($i = 0; $i < $length; $i++) {
				$index = rand(0, strlen($characters) - 1);
				$randomString .= $characters[$index];
			}
	
			return $randomString;
		}

		
		public static function GenerateID() {
			$id = self::getRandomString();

			$instances = Database::singleton()->run(
				"SELECT `id` FROM `transactions` WHERE `id` LIKE :id",
				[ ":id" => $id ]
			)->rowCount();
			
			if($instances != 0) {
				return self::GenerateID();
			} else {
				return $id;
			}
		}


		public static function CommitTransaction(TransactionType $type, User $user, int $cost = 0, Asset|null $asset = null) {
			$ta_id = self::GenerateID();

			if($asset) {
				Database::singleton()->run(
					"INSERT INTO `transactions`(`id`, `userid`, `assetcreator`, `asset`, `method`, `cost`) VALUES (:id, :uid, :auid, :aid, :method, :cost)",
					[
						":id"     => $ta_id,
						":uid"    => $user->id,
						":auid"   => $asset->creator->id,
						":aid"    => $asset->id,
						":method" => $type->ordinal(),
						":cost"   => $cost
					]
				);
			} else {
				Database::singleton()->run(
					"INSERT INTO `transactions`(`id`, `userid`, `method`, `cost`) VALUES (:id, :uid, :method, :cost)",
					[
						":id"     => $ta_id,
						":uid"    => $user->id,
						":method" => $type->ordinal(),
						":cost"   => $cost
					]
				);
			}
		}

		public static function CommitAssetTransaction(TransactionType $type, Asset $asset, User $user) {
			$cost = 0;
			switch($type) {
				case TransactionType::CONES:
					$cost = $asset->cones;
					break;
				case TransactionType::LIGHTS:
					$cost = $asset->lights;
					break;
			}
			self::CommitTransaction($type, $user, $cost, $asset);
		}

		public static function StipendLightsToUser(int $user_id, int $amount = 250) {
			include $_SERVER["DOCUMENT_ROOT"]."/private/connection.php";
			$ta_id = self::GenerateID();
			$ta_userid = $user_id;
			$ta_cost = $amount;
			$stmt = $con->prepare('INSERT INTO `transactions`(`ta_id`, `ta_userid`, `ta_currency`, `ta_cost`) VALUES (?, ?, "lights", ?)');
			$stmt->bind_param("sii", $ta_id, $ta_userid, $ta_cost);
			$stmt->execute();
		}

		public static function StipendCheckToUser(int $user_id) {
			$user = User::FromID($user_id);
			if($user != null && !$user->isBanned() && $user->pendingStipend()) {

				include $_SERVER["DOCUMENT_ROOT"]."/private/connection.php";
				$stmt_getuser = $con->prepare("SELECT * FROM `subscriptions` WHERE `userid` = ?");
				$stmt_getuser->bind_param('i', $user->id);
				$stmt_getuser->execute();
				$result = $stmt_getuser->get_result();


				if($result->num_rows == 1) {
					$stmt_user_status_check = $con->prepare('UPDATE `subscriptions` SET `lastpaytime` = now() WHERE `userid` = ?');
					$stmt_user_status_check->bind_param('i', $user->id);
					$stmt_user_status_check->execute();
				} else {
					$stmt_user_status_check = $con->prepare('INSERT INTO `subscriptions`(`userid`) VALUES (?)');
					$stmt_user_status_check->bind_param('i', $user->id);
					$stmt_user_status_check->execute();
				}

				self::CommitTransaction(TransactionType::CONES, $user, 100);
				self::CommitTransaction(TransactionType::LIGHTS, $user, 250);
			}
		}
	}
?>