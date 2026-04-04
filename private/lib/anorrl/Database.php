<?php
	namespace anorrl;
	
	/**
	 * Lifted from fubuki by parakeet
	 */
	#[\AllowDynamicProperties]
	class Database {
		private static self|null $instance = null;
		public \PDO $pdo;

		public static function singleton(): self {
			if (!self::$instance) {
				self::$instance = new Database();
			}

			return self::$instance;
		}

		function __construct() {
			$this->pdo = new \PDO(
				"mysql:host=" . \CONFIG->database->hostname . ";
				dbname=" . \CONFIG->database->name . ";
				charset=utf8mb4", 
				\CONFIG->database->username, 
				\CONFIG->database->password
			);

			$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$this->pdo->setAttribute(\PDO::ATTR_PERSISTENT, true);
		}


		function run($sql, $args = null): \PDOStatement {
			if (!$args) return $this->pdo->query($sql);
			
			$stmt = $this->pdo->prepare($sql);

			foreach ($args as $param => $value) {
				if (is_int($param)) {
					$stmt->bindValue($param + 1, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
				}
				else {
					$stmt->bindValue($param, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
				}
			}

			$stmt->execute();

			return $stmt;
		}

		function lastInsertId(): string {
			return $this->pdo->lastInsertId();
		}
	}
?>