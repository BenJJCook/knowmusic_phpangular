/* ---- Genre Page Controller ---- */
app.controller("genreCtrl", ['$scope', '$routeParams', '$http', '$sce', '$location', function ($scope, $routeParams, $http, $sce, $location) {
	
	$scope.genreId = $routeParams.g;
	$scope.showMusicInfo = false;
	$scope.chosenTrack = $sce.trustAsResourceUrl("about:blank");
	
	/* ---- Initial List Request ---- */
	$http({
        method : "GET",
        url : "inc/getsong.php",
		params : {genreId : $scope.genreId}
    }).then(function mySuccess(response) {
        $scope.playlistData = response.data;
		
		if($scope.playlistData === "Error" || $scope.playlistData.error !== undefined || $scope.playlistData === ""){
			var curPath = $location.path();
			$location.path("/error/" + $scope.genreId);
		}
		
		$scope.tracks = $scope.playlistData.tracks.items;
		
		$scope.title = $scope.playlistData.name;
		$scope.desc = $sce.trustAsHtml($scope.decodeHtml($scope.playlistData.description));
    }, function myError(response) {
		var curPath = $location.path();
		$location.path("/error/" + $scope.genreId);
    });
	
	/* ---- Overlay Trigger for Chosen Track ---- */
	$scope.showTrack = function(event){
		$scope.chosenTrack = $sce.trustAsResourceUrl("https://open.spotify.com/embed/track/" + event.currentTarget.id + "?theme=white");
		$scope.showMusicInfo = true;
		
		$scope.chosenTrackId = event.currentTarget.id;
		$scope.chosenTrackName = event.currentTarget.attributes["data-track-name"].value;
		$scope.chosenTrackArtists = event.currentTarget.attributes["data-track-artists"].value;
	}
	
	/* ---- Hide Overlay ---- */
	$scope.hideTrack = function(){
		$scope.chosenTrack = $sce.trustAsResourceUrl("about:blank");
		$scope.showMusicInfo = false;
	}
	
	/* ---- Converting HTML entities to plain text (should probably put in to a service, later) ---- */
	$scope.decodeHtml = function(toDecode){
		var txt = document.createElement("textarea");
		txt.innerHTML = toDecode;
		return txt.value;
	}
	
}]);