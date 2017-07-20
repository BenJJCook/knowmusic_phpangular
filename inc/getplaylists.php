<?php
	/**
	 * Retrieve a list of the playlists and relevant information, from the database
	 *
	 * This file will make a connection to the database using the credentials held in db_data.
	 * It will then retrieve all the information on the known playlists, and return them in a JSON formed array.
	 *
	 * @return string A JSON list of the playlist information. On error, it simply returns "Error".
	 */
	
	try{
		
		// Get Database info and convert to associative array
		$rawJson = file_get_contents('../data/db_data');
		$dbData = json_decode($rawJson, true);
		$dbHost = $dbData['db_host'];
		$dbName = $dbData['db_name'];
		$dbUser = $dbData['db_user'];
		$dbPass = $dbData['db_pass'];
		
		// Attempt database connection
		try 
		{
			$pdo = new PDO('mysql:host='.$dbHost.';dbname='.$dbName, $dbUser, $dbPass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}
		catch (PDOException $e) 
		{
			echo 'Error';
			exit();
		}
		
		// Execute query to get all playlist information, sort in ascending order
		$sql 	= 'SELECT * FROM playlists ORDER BY playlist_name ASC';
		$stmt 	= $pdo->prepare($sql);
		$stmt->execute();
		
		// Place all playlists in an array
		$playlistCollection = array();
		while ($row = $stmt->fetch())
		{
			$playlistCollection[] = $row;
		}

		// Disconnect DB connection
		$pdo = null;
		
		// Encode data as JSON, and return list
		echo json_encode($playlistCollection);
		
	} catch(Exception $e) {
		
		// On error, simply return "Error"
		echo "Error";
		
	}
?>