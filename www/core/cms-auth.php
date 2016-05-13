<?php
session_start();
require_once("db/connect.php");
require_once("m-user.php");
require_once("util.php");
?>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset='utf-8'">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
</head>
<?php
if (isset($_POST['login']) && isset($_POST['password'])) {
    $login = StringUtils::convert($_POST['login'], 'string');
    $password = StringUtils::convert($_POST['password'], 'string');

    $login = DB::$mysqli->real_escape_string($login);
    $password = DB::$mysqli->real_escape_string($password);

    $user = Users::find_user_by_login_password($login, $password);

    if (isset($user) && $user != null) {
        $_SESSION["login"] = $user->login;
        $_SESSION["role"] = $user->role;
    } else {
        $error_message = "Направильный логин / пароль!";
    }
}

if (!isset($_SESSION['login']) && !isset($_SESSION['role'])) {
?>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4">
            <form id='auth' method='post' action=<?= $_SERVER['REQUEST_URI'] ?>>
                <? if (isset($error_message)) { ?>
                    <div class="form-group has-error">
                        <div class="">
                            <p class="form-control-static"><?= $error_message ?></p>
                        </div>
                    </div>
                <? } ?>

                <div class="form-group">
                    <label>Логин:</label>
                    <div><input type='text' name='login' class="form-control"></div>
                </div>
                <div class="form-group">
                    <label>Пароль:</label>
                    <div><input type='password' name='password' class="form-control"></div>
                </div>
                <div class="form-group">

                    <td colspan=2><input type='submit' value='Войти' class="btn btn-primary"></td>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="../script/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>
<?php
exit(0);
}
?>
</html>