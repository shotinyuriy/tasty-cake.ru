<?php
session_start();
require_once("m-user.php");
require_once("v-user.php");
require_once("v-error.php");
require_once("util.php");

if (isset($_REQUEST["method"])) {
    $method = StringUtils::convert($_REQUEST["method"], 'string');

    if ($method == 'save') {
        save();
    } elseif ($method == 'edit') {
        edit();
    } elseif ($method == 'list') {
        list_users();
    }
}

function save() {
    include("cms-result-header.php");

    $login = isset( $_POST[ "login" ] ) ? StringUtils::convert( $_POST[ "login" ], 'string' ) : null;
    $role = isset( $_POST[ "role" ] ) ? StringUtils::convert( $_POST[ "role" ], 'string' ) : null;
    $old_password = isset( $_POST[ "old_password" ] ) ? StringUtils::convert( $_POST[ "old_password" ], 'string' ) : null;
    $new_password = isset( $_POST[ "new_password" ] ) ? StringUtils::convert( $_POST[ "new_password" ], 'string' ) : null;
    $confirm_password = isset( $_POST[ "confirm_password" ] ) ? StringUtils::convert( $_POST[ "confirm_password" ], 'string' ) : null;

    $error = false;

    if(isset($new_password) && $new_password != null ) {
        if($new_password != $confirm_password) {
            $error = true;
            ErrorView::print_error_message("Пароли не совпадают!");
        }
    }

    if(!$error && isset($old_password) && $old_password != null ) {
        $user = Users::find_user_by_login_password($login, $old_password);
        if(isset($user) && $user != null) {
            $user->new_password = $new_password;
        } else {
            $error = true;
            ErrorView::print_error_message("Старый пароль указан неправильно!");
        }
    } else {
        $user = new User();
        $user->login = $login;
        $user->role = $role;
        $user->new_password = $new_password;
    }

    if(!$error) {
        $user = Users::save_user( $user );
    }

    include("cms-result-footer.php");
}

function edit() {

    $login = isset( $_REQUEST[ "login" ] ) ?  StringUtils::convert( $_REQUEST[ "login" ], 'string' ) : null;

    $user = null;
    if( $login != null ) {
        $user = Users::find_user_by_login( $login );
    }

    if( $user == null ) {
        $user = new User();
    }

    UserView::edit_form( $user );
}

function list_users() {
	if(isset($_SESSION["role"]) && $_SESSION["role"]==User::ADMINISTRATOR) {
		$users = Users::get_all_users();
		UserView::users_to_table($users);
	} else {
		ErrorView::print_error_message("У вас недостаточно прав на просмотр раздела!");
	}
}

?>