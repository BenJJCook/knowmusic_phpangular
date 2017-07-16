<?php
	class SessionInfo {
		private $client_id = 'API_ID';
		private $client_secret = 'API_SECRET';
		private $redirect_uri = 'CALLBACK_URI';
		
		private $account_url = 'https://accounts.spotify.com/api/token';
		private $api_url = 'https://api.spotify.com/v1';
		
		public function __construct($client_id, $client_secret, $redirect_uri){
			$this->setClientId($client_id);
			$this->setClientSecret($client_secret);
			$this->setRedirectUri($redirect_uri);
		} 
		
		public function getClientId(){
			return $this->client_id;
		}
		
		public function setClientId($client_id){
			$this->client_id = $client_id;
		}
		
		public function getClientSecret(){
			return $this->client_secret;
		}
		
		public function setClientSecret($client_secret){
			$this->client_secret = $client_secret;
		}
	
		public function getRedirectUri(){
			return $this->redirect_uri;
		}
		
		public function setRedirectUri($redirect_uri){
			$this->redirect_uri = $redirect_uri;
		}
		
		public function requestAuthorize(){
			$params = [
				'client_id' => $this->getClientId(),
				'client_secret' => $this->getClientSecret(),
				'code' => $authorizationCode,
				'grant_type' => 'authorization_code',
				'redirect_uri' => $this->getRedirectUri(),
			];
		}
		
		public function requestAccessToken()
		{
			$crl = curl_init();
			$encodedID = base64_encode($this->getClientId() . ":" . $this->getClientSecret());
			
			$hdr = array();
			$hdr = array('Authorization: Basic ' . $encodedID);
			$data = array('grant_type' => 'client_credentials');
			
			curl_setopt($crl, CURLOPT_URL, $this->account_url);
			curl_setopt($crl, CURLOPT_HTTPHEADER, $hdr);
			curl_setopt($crl, CURLOPT_POST, true);
			curl_setopt($crl, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
			
			$json = curl_exec($crl);
			
			curl_close($crl);
			
			$responseArray = json_decode($json, true);
			
			$_SESSION['tokenRetrieved'] = true;
			$_SESSION['tokenEndTime'] = time() + (int)$responseArray['expires_in'] - 300;
			$_SESSION['tokenValue'] = $responseArray['access_token'];
		}
		
		public function requestPlaylist($playlistId)
		{
			/*$data = [
				'client_id' => $this->getClientId(),
				'client_secret' => $this->getClientSecret(),
				'code' => $_SESSION['tokenValue'],
				'grant_type' => 'authorization_code',
				'redirect_uri' => $this->getRedirectUri(),
			];*/
			
			$crl = curl_init();
			
			$hdr = array();
			$hdr = array('Authorization: Bearer ' . $_SESSION['tokenValue']);
			
			curl_setopt($crl, CURLOPT_URL, $this->api_url . "/users/thechaoticlord/playlists/" . $playlistId);
			curl_setopt($crl, CURLOPT_HTTPHEADER, $hdr);
			curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
			
			$json = curl_exec($crl);

			echo curl_error($crl);
			
			curl_close($crl);
			
			return $json;
		}
	}

?>