<?php
	/**
	 * Retrieve a list of the songs from the playlist with the given Spotify ID
	 *
	 * This file will ensure we have a Spotify access token (if not, it will request a new one), then using that token,
	 * it will request a list of songs in JSON format, that correspond to the playlist represented by the given ID.
	 *
	 * @return string A JSON list of the playlist's information. On error, it simply returns "Error".
	 */

	// Start the session
	session_start();
	
	try{
		
		// Using the request.php file
		require('request.php');
			
		//Get the given playlist ID (genreId)
		$genreId = $_GET['genreId'];
		
		// Get the API access information from api_data and convert to associative array
		$rawJson = file_get_contents('../data/api_data');
		$sessionData = json_decode($rawJson, true);
		
		// Start a new Spotify API access session
		$si = new SessionInfo($sessionData['api_id'], $sessionData['api_secret'], $sessionData['callback_url']);
		
		// Check if we have a valid access token (if not, request a new one)
		if(isset($_SESSION['tokenRetrieved'])){
			$endTime = $_SESSION['tokenEndTime'];
			if (time() > $endTime) {
				$si->requestAccessToken();
			}
		} else {
			$si->requestAccessToken();
		}
		
		// Request the song information in the given playlist
		$jsonPlaylist = $si->requestPlaylist($genreId);
		
		// Return the JSON formed list of songs
		echo $jsonPlaylist;
		
	} catch (Exception $e) {
		
		// On error, simply return "Error"
		echo "Error";
		
	}
?>