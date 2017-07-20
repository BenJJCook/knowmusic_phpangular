app.controller("mainCtrl", ['$scope', '$http', '$location', function ($scope, $http, $location) {
	
	$http({
        method : "GET",
        url : "inc/getplaylists.php",
    }).then(function mySuccess(response) {
        $scope.playlistData = response.data;
		
		if($scope.playlistData === "Error"){
			var curPath = $location.path();
			$location.path("/error/" + "main");
		}
		
		if($scope.playlistData.length < 12){
			var diff = 12 - $scope.playlistData.length;
			for(var i = 0; i < diff; i++){
				$scope.playlistData.push(
					{
						playlist_bg_name:"cs_bg.png", 
						playlist_icon_name:"cs.png", 
						playlist_name:"Coming Soon", 
						playlist_spotify_id:""
					}
				);
			}
		}
    }, function myError(response) {
        var curPath = $location.path();
		$location.path("/error/" + "main");
    });
	
	
}]);