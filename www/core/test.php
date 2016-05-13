<?php
	echo $_SERVER[ 'DOCUMENT_ROOT' ];
	
	$match = preg_match('/\..{1,5}$/', 'short.jpg', $found);
	
	echo 'matches: '.$match;
	if( isset( $found ) ) {
		$ext = $found[0];
		echo $ext;
	}
	
	
					
?>