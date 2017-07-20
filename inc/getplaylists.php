<?php

	try{
		$rawJson = file_get_contents('../data/db_data');
		$dbData = json_decode($rawJson, true);

		$dbHost = $dbData['db_host'];
		$dbName = $dbData['db_name'];
		$dbUser = $dbData['db_user'];
		$dbPass = $dbData['db_pass'];
		
		try 
		{
			$pdo = new PDO('mysql:host='.$dbHost.';dbname='.$dbName, $dbUser, $dbPass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}
		catch (PDOException $e) 
		{
			echo 'Error';
			exit();
		}
		
		$sql 	= 'SELECT * FROM playlists ORDER BY playlist_name ASC';
		$stmt 	= $pdo->prepare($sql);
		$stmt->execute();
		
		$playlistCollection = array();
		
		while ($row = $stmt->fetch())
		{
			$playlistCollection[] = $row;
		}

		$pdo = null;
		
		echo json_encode($playlistCollection);
	} catch(Exception $e) {
		echo "Error";
	}

?>