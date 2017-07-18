<?php

	session_start();
	
	require('request.php');
		
	$genre = $_GET['genre'];
	
	$si = new SessionInfo('API_ID', 'API_SECRET', 'CALLBACK_URI');
	
	if(isset($_SESSION['tokenRetrieved'])){
		$endTime = $_SESSION['tokenEndTime'];
		if (time() > $endTime) {
			$si->requestAccessToken();
		}
	} else {
		$si->requestAccessToken();
	}
	
	$genreCode = '';
	
	if($genre == 'forties') {
		$genreCode = '5p2NoLNIffjvZwTubz08lf';
	} elseif($genre == 'classical') {
		$genreCode = '2RCz0WodupC8knkFN6hDg1';
	} elseif($genre == 'hardstyle'){
		$genreCode = '5MbHSyWiovmsQJTPOzhoxa';
	}
	
	$jsonPlaylist = $si->requestPlaylist($genreCode);
	
	echo $jsonPlaylist;
?>