var app = angular.module("app", ["ngRoute"]);

app.config(function ($routeProvider,$locationProvider) {	
    $locationProvider.hashPrefix('');
    $routeProvider
    .when("/", {
        templateUrl : "templates/main.html",
		controller	: "mainCtrl"
    })
    .when("/genre/:g", {
        templateUrl : "templates/genre.html",
		controller	: "genreCtrl"
    })
	.when("/error/:code", {
		templateUrl	: "templates/error.html",
		controller	: "errorCtrl"
	})
	.otherwise({
		templateUrl : "templates/main.html",
		controller	: "mainCtrl"
	});
});

app.controller("mainCtrl", function ($scope, $http, $location) {
	
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
	
	
});

app.controller("genreCtrl", function ($scope, $routeParams, $http, $sce, $location) {
	$scope.genreId = $routeParams.g;
	$scope.showMusicInfo = false;
	$scope.chosenTrack = $sce.trustAsResourceUrl("about:blank");
	
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
	
	$scope.showTrack = function(event){
		$scope.chosenTrack = $sce.trustAsResourceUrl("https://open.spotify.com/embed/track/" + event.currentTarget.id + "?theme=white");
		$scope.showMusicInfo = true;
		
		$scope.chosenTrackId = event.currentTarget.id;
		$scope.chosenTrackName = event.currentTarget.attributes["data-track-name"].value;
		$scope.chosenTrackArtists = event.currentTarget.attributes["data-track-artists"].value;
	}
	
	$scope.hideTrack = function(){
		$scope.chosenTrack = $sce.trustAsResourceUrl("about:blank");
		$scope.showMusicInfo = false;
	}
	
	$scope.decodeHtml = function(toDecode){
		var txt = document.createElement("textarea");
		txt.innerHTML = toDecode;
		return txt.value;
	}
	
});

app.controller("errorCtrl", function ($scope) {});

app.filter('artists', function() {
  return function(input) {

    var output = "";
	for(var j = 0; j < input.length; j++){
		output += input[j].name;
		if(j !== input.length-1) {
			output += ", ";
		}
	}

    return output;

  }

});