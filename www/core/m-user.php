<?php
require_once ("db/connect.php");
require ("v-error.php");
	class User {
		const OPERATOR = "operator";
		const ADMINISTRATOR = "admin";

		public $id;
		public $login;
		public $role;

		public $old_password;
		public $new_password;
		public $confirm_password;

		public function mysql_insert() {

			return "INSERT INTO `user`(login, role, password) VALUES("
			."'".$this->login."', "
			."'".$this->role."', "
			."'".$this->new_password."');";
		}

		public function mysql_update() {
			if( !isset( $this->id ) ) {
				return "";
			}

			return "UPDATE `user` SET "
			."login = '".$this->login."', "
			."password = '".$this->new_password."', "
			."role = '".$this->role."' "
			." WHERE id = '".$this->id."';";
		}
	}

class Users {
	public static function generate_id() {
		$query = "SELECT MAX(`id`+1) as `id` FROM `user`";

		$result = DB::$mysqli->query( $query );

		if( DB::$mysqli->error ) {
			print_r( DB::$mysqli->error );
			echo $query;
			return 0;
		}

		while( $row =  $result->fetch_assoc() ) {
			$last_id = $row[ "id" ];
		}

		if(!isset($last_id) || $last_id == null) {
			$last_id = 1;
		}
		return $last_id;
	}
	
	public static function get_all_users() {
		$query = "SELECT u.`role`, u.`id`, u.`login` FROM `user` u;";
		
		$users = array();
		
		$result = DB::$mysqli->query($query);
		if (!DB::$mysqli->error) {
			while ($row = $result->fetch_assoc()) {
				$user = new User();
				$user->role = $row["role"];
				$user->login = $row["login"];
				$user->id = $row["id"];
				
				$users[$user->id] = $user;
			}
			
			return $users;
		} else {
			echo "<center>";
			print_r(DB::$mysqli->error);
			echo "</center>";
			return null;
		}
	}

	public static function find_user_by_login($login) {
		$query = "SELECT u.`role`, u.`id`, u.`login` FROM `user` u WHERE u.`login` = '" . $login . "';";

		$result = DB::$mysqli->query($query);
		if (!DB::$mysqli->error) {
			while ($row = $result->fetch_assoc()) {
				$role = $row["role"];
				$login = $row["login"];
				$id = $row["id"];
			}

			if (isset($role)) {
				$user = new User();
				$user->role = $role;
				$user->login = $login;
				$user->id = $id;
				return $user;
			}
		} else {
			echo "<center>";
			print_r(DB::$mysqli->error);
			echo "</center>";
			return null;
		}
	}

	public static function find_user_by_login_password($login, $password) {
		$query = "SELECT `role`, `id` FROM user WHERE `login` = '" . $login . "' AND password = '" . $password . "';";

		$result = DB::$mysqli->query($query);
		if (!DB::$mysqli->error) {
			while ($row = $result->fetch_assoc()) {
				$role = $row["role"];
				$id = $row["id"];
			}

			if (isset($role)) {
				$user = new User();
				$user->id = $id;
				$user->role = $role;
				$user->login = $login;
				return $user;
			}
		} else {
			echo "<center>";
			print_r(DB::$mysqli->error);
			echo "</center>";
			return null;
		}
	}

	public static function save_user(User $user ) {
		if( isset( $user ) ) {
			$query = "";
			if( isset( $user->id ) && strlen( $user->id ) > 0 ) {
				$query = $user->mysql_update();
			} else {
				$query = $user->mysql_insert();
			}

			DB::$mysqli->query( $query );

			if( DB::$mysqli->error ) {
				ErrorView::print_error_message("query = <br>".$query);
				print_r( DB::$mysqli->error );
				return null;
			}

			return $user;
		} else {
			echo "Ошибка при сохранении пользовтеля! Пользователь не указан!";
			return null;
		}
	}
}
?>