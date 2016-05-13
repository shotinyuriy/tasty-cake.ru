<?php
	session_start();
	require_once("util.php");
	
	if( isset( $_REQUEST["categoryId"] ) ) {
		$_SESSION["category_id"] = StringUtils::convert( $_REQUEST["categoryId"], 'string' );
	}

	if( isset( $_SESSION["category_id"] ) ) {
		echo( "category id = ".$_SESSION["category_id"] );
	} else {
		echo "categort id is not set";
	}
?>