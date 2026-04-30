<?php

	namespace anorrl\utilities;

	use anorrl\User;
	use anorrl\Database;

	/**
	 * Utilities for User stuff<br>
	 * Paging, Logging, Registering etc.
	 */
	class UserUtils {
		
		/**
		 * Creates a 255 long random strings from a character set to be used for the security of a user
		 * @return string Security key
		 */
		public static function GenerateSecurityKey(): string {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_?-/=;#!';
			$randomString = '';
			
			for ($i = 0; $i < 255; $i++) {
				$index = rand(0, strlen($characters) - 1);
				$randomString .= $characters[$index];
			}
	
			return $randomString;
		}

		/**
		 * Creates a user and does checks to ensure that all data given is correct.
		 * 
		 * If some data is invalid, it will return an array of the errors.
		 * @param string $username
		 * @param string $password
		 * @param string $confirm_password
		 * @param string $accesskey
		 * @return array|string
		 */
		public static function RegisterUser(string $username, string $password, string $confirm_password, string $accesskey): string|array {
			$errors = [];

			if(self::IsUsernameValid($username)) {
				if(!self::IsUsernameAvailable($username)) {
					$errors["username"] = "Username has already been taken!";
				}
			} else {
				$errors["username"] = "a-z A-Z 0-9 and 3-20 characters only!";
			}

			if(strlen($password) >= 7) {
				if(strcmp($password, $confirm_password) !== 0) {
					$errors["password"] = "Passwords do not match!";
				}
			} else {
				$errors["password"] = "Password must be minimum 7 characters!";
			}

			if(!self::IsValidKey($accesskey)) {
				$errors["accesskey"] = "Invalid access key.";
			}

			if(sizeof($errors) != 0) {
				return $errors;
			}

			$discordid = self::UseAccessKey($accesskey);
			$hashedpass = password_hash($password, PASSWORD_ARGON2ID);
			$securitykey = self::GenerateSecurityKey();
			
			if(Database::singleton()->run(
				"INSERT INTO `users`(`name`, `blurb`, `discord`, `password`, `security`) VALUES (:name,'',:discord,:password,:security);",
				[
					":name" => $username,
					":discord" => $discordid,
					":password" => $hashedpass,
					":security" => $securitykey
				]
			)->errorInfo()[0] == SQL_ALLOK) {
				self::SetCookies($securitykey);
				return "success"; // todo return ["error" => false] bc what the fuck is this
			}

			return ['unknown'=>"Something went wrong!"];
		}

		/**
		 * Verify details given and set cookies to allow logins.
		 * @param mixed $username
		 * @param mixed $password
		 * @return string|array
		 */
		public static function LoginUser(string $username, string $password): string|array {
			$errors = [];

			$pass_username = trim($username);
			$pass_password = trim($password);

			$pass_username_length = strlen($pass_username);
			$pass_password_length = strlen($pass_password);

			if($pass_username_length == 0) {
				$errors["username"] = "Username field cannot be empty!";
			} 
			else if(!preg_match("/^[a-zA-Z0-9]{3,20}$/", $pass_username)) {
				$errors["username"] = "a-z A-Z 0-9 and 3-20 characters only!";
			}

			if($pass_password_length == 0) {
				$errors["password"] = "Password field cannot be empty!";
			}

			if(sizeof($errors) != 0) {
				return $errors;
			}

			$user = User::FromNamePercise($username);

			if($user) {
				if(password_verify($pass_password, $user->password)) {
					self::SetCookies($user->security_key);
					if(session_status() != PHP_SESSION_ACTIVE) {
						session_start();
					}

					$_SESSION['SESSION_TOKEN_YAA'] = $user->security_key;
					return  ['login' => $user->security_key]; // why what
				}
			}

			return ['login' => "Incorrect details provided!"];
		}

		/**
		 * Summary of IsValidKey
		 * @param mixed $accesskey
		 * @return bool
		 */
		static function IsValidKey(string $accesskey): bool {
			return Database::singleton()->run(
				'SELECT `key` FROM `accesskeys` WHERE `key` = :key',
				[":key" => $accesskey]
			)->rowCount() != 0;
		}

		/**
		 * Uses the access key provided. Will return the discord user id it was created for.
		 * @param string $accesskey
		 * @return string|null
		 */
		static function UseAccessKey(string $accesskey): string|null {
			$db = Database::singleton();
			// yup
			$discorduid =  $db->run("SELECT `discorduid` FROM `accesskeys` WHERE `key` = :key", [":key" => $accesskey])->fetchObject()->discorduid;
			/* use key */  $db->run("DELETE FROM `accesskeys` WHERE `key` = :key", [":key" => $accesskey]);

			return $discorduid;
		}

		/**
		 * Checks if given username is not being already used.
		 * @param string $username
		 * @return bool True if it's not being used
		 */
		public static function IsUsernameAvailable(string $username): bool {
			return User::FromName($username) == null;
		}

		public static function IsUsernameValid(string $username): bool {
			return preg_match("/^[a-zA-Z0-9]{3,20}$/", $username);
		}
		
		public static function RetrieveUser(): User|null {
			if(session_status() != PHP_SESSION_ACTIVE) {
				session_start();
			}

			$user = null;

			if(isset($_COOKIE['ANORRLSECURITY'])) {
				$user = User::FromSecurityKey(urldecode($_COOKIE['ANORRLSECURITY']));	
			} else if(isset($_SESSION['SESSION_TOKEN_YAA'])) {
				$user = User::FromSecurityKey($_SESSION['SESSION_TOKEN_YAA']);	
			}

			if((isset($_COOKIE['ANORRLSECURITY']) || isset($_SESSION['SESSION_TOKEN_YAA'])) && $user == null) {
				self::RemoveCookies();
			}

			if($user) {
				$user->registerAction("Website");
			}
			
			return $user;
		}

		static function SetCookies(string $security): void {
			unset($_COOKIE['ANORRLSECURITY']);
			setcookie("ANORRLSECURITY", $security, time() + (460800* 30), "/", ".lambda.cam");
		}

		public static function RemoveCookies(): void {
			unset($_COOKIE['ANORRLSECURITY']);
			setcookie("ANORRLSECURITY", "", -1, "/", ".lambda.cam");
		}

		public static function GetRandomUsers(int $count): array {
			$fetch_users = Database::singleton()->run(
				"SELECT id FROM `users` ORDER BY RAND() LIMIT :limit",
				[ ":limit" => $count ]
			)->fetchAll(\PDO::FETCH_OBJ);

			$users =  [];
			foreach($fetch_users as $obj_user) {
				$users[] = User::FromID($obj_user->id);
			}

			return $users;
		}


		public static function GetLatestUsers(int $count): array {
			$fetch_users = Database::singleton()->run(
				"SELECT * FROM `users` ORDER BY `joindate` DESC LIMIT :limit",
				[ ":limit" => $count ]
			)->fetchAll(\PDO::FETCH_OBJ);

			$users =  [];
			foreach($fetch_users as $obj_user) {
				$users[] = User::FromID($obj_user->id);
			}

			return $users;
		}

		public static function GetAllUsersPaged(int $page, int $count, string $query = ""): array|null {
			$queryfiltered = "%$query%";
			if($queryfiltered == "%%") {
				$queryfiltered = "%";
			}

			$db = Database::singleton();

			$fetch_users = $db->run("SELECT `id` FROM `users`")->fetchAll(\PDO::FETCH_OBJ);
			
			foreach($fetch_users as $obj_user) {
				User::FromID($obj_user->id)->isOnline();
			}

			$userids = $db->run(
				"SELECT `users`.`id` FROM `users`, `activity` WHERE `activity`.`userid` = `users`.`id` AND `name` LIKE :query ORDER BY `users`.`online` DESC, `activity`.`action_time` DESC LIMIT :page, :rows",
				[
					":query" => $queryfiltered,
					":page" => (($page-1)*$count),
					":rows" => $count
				]
			)->fetchAll(\PDO::FETCH_OBJ);

			$users = [];

			foreach($userids as $row) {
				$users[] = User::FromID($row->id);
			}

			return $users;
		}

		public static function GetAllUsers(string $query = ""): array|null {
			$queryfiltered = "%$query%";

			$result_array = [];

			$getallusers = Database::singleton()->run(
				"SELECT * FROM `users` WHERE `name` LIKE :query",
				[
					":query" => $queryfiltered
				]
			)->fetchAll(\PDO::FETCH_ASSOC);

			foreach($getallusers as $user) {
				$result_array[] = new User($user);
			}
			
			return $result_array;
		}
	}

?>
