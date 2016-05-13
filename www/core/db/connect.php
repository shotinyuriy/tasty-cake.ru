<?php
	require_once("settings.php");
	
	class DB {
		static $mysqli;
	
		public static function connect() { 
			self::$mysqli = new mysqli(MAIN_DB::DB_LOCATION, MAIN_DB::DB_USER, MAIN_DB::DB_PASSWORD, MAIN_DB::DB_NAME);
			
			if( self::$mysqli->connect_errno ) {
				echo "DB connection error!";
			} else {
				self::$mysqli->query("set character_set_client='utf8';");
				self::$mysqli->query("set character_set_results='utf8';");
				self::$mysqli->query("set collation_connection='utf8_general_ci';");
				self::$mysqli->query("set names 'utf8';");
			}
		}
	}
	
	DB::connect();
?>