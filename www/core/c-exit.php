<?php
	session_start();
	unset( $_SESSION['user'] );
	unset( $_SESSION['role'] );
	session_destroy();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset='utf-8'">
<meta http-equiv="refresh" content="0; url=cms.php" />
</head>
</html>