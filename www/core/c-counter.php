<?php
	session_start();
	require_once( "db/connect.php" );
	
	if( !isset( $_SESSION[ "session_start" ] ) ) {
		$session_id = session_id();
		
		$login = isset( $_SESSION[ "login" ] ) ? $_SESSION[ "login" ] : "" ; 
		$role = isset( $_SESSION[ "role" ] ) ? $_SESSION[ "role" ] : "" ;
		
		$query = "INSERT INTO stats( `login`, `role`, `session_start`, `session_id` ) VALUES ("
			." '".$login."', '".$role."', NOW(), '".$session_id."' ); ";
			
		DB::$mysqli->query( $query );
		
		if( DB::$mysqli->error ) {
			echo $query."<br>";
			print_r( DB::$mysqli->error );
			exit( 0 );
		}
		
		$id = DB::$mysqli->insert_id;
		
		$query = "SELECT `session_start` FROM `stats` WHERE `id` = ".$id.";";
		
		$result = DB::$mysqli->query( $query );
		
		if( DB::$mysqli->error ) {
			echo $query."<br>";
			print_r( DB::$mysqli->error );
			exit( 0 );
		}
		
		if( $result )
		while( $row = $result->fetch_assoc() ) {
			$session_start = $row[ "session_start" ];
		}
		
		$_SESSION[ "session_start" ] = $session_start;
		$_SESSION[ "stats_id" ] = $id;
	}
	
	
?>