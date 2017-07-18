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
	.otherwise({
		templateUrl : "templates/main.html",
		controller	: "mainCtrl"
	});
});

app.controller("mainCtrl", function ($scope) {
	
	function genre(genreUrl, genreIcon, genreBg, genreName) {
		this.genreUrl = genreUrl;
		this.genreIcon = genreIcon;
		this.genreBg = genreBg;
		this.genreName = genreName;
	}
	
	var classical = new genre("#/genre/classical", "violin2.png", "violin_bg.jpg", "Classical");
	var forties = new genre("#/genre/forties", "forty.png", "forty_bg.png", "1940's");
	var hardstyle = new genre("#/genre/hardstyle", "hrdstyl.png", "hrdstyl_bg.jpg", "Hardstyle");
	
	var genreList = [classical, forties, hardstyle];
	for(var i = 0; i < 9; i++){
		genreList.push(new genre("#/", "cs.png", "cs_bg.png", "Coming Soon"));
	}
	
	$scope.genres = genreList;
	
	
});

app.controller("genreCtrl", function ($scope, $routeParams, $http, $sce) {
	$scope.genre = $routeParams.g;
	$scope.showMusicInfo = false;
	$scope.chosenTrack = $sce.trustAsResourceUrl("about:blank");
	
	$http({
        method : "GET",
        url : "inc/getsong.php",
		params : {genre : $scope.genre}
    }).then(function mySuccess(response) {
        $scope.playlistData = response.data;
		$scope.tracks = $scope.playlistData.tracks.items;
		
		$scope.title = $scope.playlistData.name;
		$scope.desc = $sce.trustAsHtml($scope.decodeHtml($scope.playlistData.description));
    }, function myError(response) {
        $scope.playlistData = response.statusText;
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