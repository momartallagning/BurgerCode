<?php 

	class Database {

		private static $dbHost = "localhost";
		private static $dbName = "burger_code";
		private static $dbUser = "root";
		private static $dbUserPassword = "sweetdream";
		private static $connection = null;

		public static function connect() {
			try {
				self::$connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName, self::$dbUser, self::$dbUserPassword, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			} catch (PDOException $e) {
				die($e->getMessage());
			}
			return self::$connection;
		}

		public static function disconnect() {
			self::$connection = null;
		}
	
	}
	
?>