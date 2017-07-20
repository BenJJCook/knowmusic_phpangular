<?php
	/**
	 * A class to handle the Spotify API
	 *
	 * This class handles the Spotify API, by holding information on the current Spotify session, creating the access token, 
	 * and requesting information on the given playlist ID.
	 */
	class SessionInfo {
		private $client_id = '';
		private $client_secret = '';
		private $redirect_uri = '';
		
		private $account_url = 'https://accounts.spotify.com/api/token';
		private $api_url = 'https://api.spotify.com/v1';
		
		/**
		 * The SessionInfo constructor
		 *
		 * @param string $client_id The Spotify Client ID, stored in the api_db file
		 * @param string $client_secret The Spotify Client Secret, stored in the api_db file
		 * @param string $client_id The Spotify API redirect URI, stored in the api_db file (not used in the current implementation)
		 */
		public function __construct($client_id, $client_secret, $redirect_uri){
			$this->setClientId($client_id);
			$this->setClientSecret($client_secret);
			$this->setRedirectUri($redirect_uri);
		} 
		
		/**
		 * Getter for the Client ID
		 *
		 * @return The Client ID
		 */
		public function getClientId(){
			return $this->client_id;
		}
		
		/**
		 * Setter for the Client ID
		 *
		 * @param $client_id The new Client ID
		 */
		public function setClientId($client_id){
			$this->client_id = $client_id;
		}
		
		/**
		 * Getter for the Client Secret
		 *
		 * @return The Client Secret
		 */
		public function getClientSecret(){
			return $this->client_secret;
		}
		
		/**
		 * Setter for the Client Secret
		 *
		 * @param $client_id The new Client Secret
		 */
		public function setClientSecret($client_secret){
			$this->client_secret = $client_secret;
		}

		/**
		 * Getter for the Redirect URI
		 *
		 * @return The Redirect URI
		 */
		public function getRedirectUri(){
			return $this->redirect_uri;
		}
		
		/**
		 * Setter for the Redirect URI
		 *
		 * @param $client_id The new Redirect URI
		 */
		public function setRedirectUri($redirect_uri){
			$this->redirect_uri = $redirect_uri;
		}
		
		/**
		 * Request a new access token, and store the token information in the Session variables:
		 * tokenRetrieved, tokenEndTime and tokenValue.
		 */
		public function requestAccessToken()
		{
			// Create new curl instance
			$crl = curl_init();
			
			// Encode Client ID and Secret for Authorization Header
			$encodedID = base64_encode($this->getClientId() . ":" . $this->getClientSecret());
			
			// Construct Authorization Header
			$hdr = array();
			$hdr = array('Authorization: Basic ' . $encodedID);
			
			// Set grant_type to get client credentials
			$data = array('grant_type' => 'client_credentials');
			
			// Set up curl parameters
			curl_setopt($crl, CURLOPT_URL, $this->account_url); 			// Set URL
			curl_setopt($crl, CURLOPT_HTTPHEADER, $hdr);					// Attach Header
			curl_setopt($crl, CURLOPT_POST, true);							// Set as POST type
			curl_setopt($crl, CURLOPT_POSTFIELDS, http_build_query($data));	// Set POST fields
			curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);				// Set up to retrieve return value
			
			// Send request and store return in $json
			$json = curl_exec($crl);
			
			// Close curl connection
			curl_close($crl);
			
			// Convert response to associative array
			$responseArray = json_decode($json, true);
			
			// Store response in SESSION variables
			$_SESSION['tokenRetrieved'] = true;
			$_SESSION['tokenEndTime'] = time() + (int)$responseArray['expires_in'] - 300;
			$_SESSION['tokenValue'] = $responseArray['access_token'];
		}
		
		
		/**
		 * Send request to Spotify for track information on the playlist associated with the given ID
		 *
		 * @param $playlistId The ID of the Spotify playlist from which to get the track info
		 *
		 * @return A JSON formed array of track info for the playlist
		 */
		public function requestPlaylist($playlistId)
		{		
			// Create new curl instance
			$crl = curl_init();
			
			// Form Authorization Header using the current Access Token
			$hdr = array();
			$hdr = array('Authorization: Bearer ' . $_SESSION['tokenValue']);
			
			// Set up curl parameters
			curl_setopt($crl, CURLOPT_URL, $this->api_url . "/users/knowmusicsite/playlists/" . $playlistId); 	// Set URL
			curl_setopt($crl, CURLOPT_HTTPHEADER, $hdr);														// Attach Header
			curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);													// Set up to retrieve return value
			
			// Send request and store return in $json
			$json = curl_exec($crl);
			
			// Close curl connection
			curl_close($crl);
			
			// Return list of track info
			return $json;
		}
	}
?>