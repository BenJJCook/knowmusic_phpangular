<?php

	session_start();
	
	try{
		require('request.php');
			
		$genreId = $_GET['genreId'];
		
		$rawJson = file_get_contents('../data/api_data');
		$sessionData = json_decode($rawJson, true);
		
		$si = new SessionInfo($sessionData['api_id'], $sessionData['api_secret'], $sessionData['callback_url']);
		
		if(isset($_SESSION['tokenRetrieved'])){
			$endTime = $_SESSION['tokenEndTime'];
			if (time() > $endTime) {
				$si->requestAccessToken();
			}
		} else {
			$si->requestAccessToken();
		}
		
		$jsonPlaylist = $si->requestPlaylist($genreId);
		
		echo $jsonPlaylist;
	} catch (Exception $e) {
		echo "Error";
	}
?>