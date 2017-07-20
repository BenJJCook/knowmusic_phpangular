/* ---- Angular Start ---- */
var app = angular.module("app", ["ngRoute"]);

/* ---- Routing Setup ---- */
app.config(['$routeProvider', '$locationProvider', function ($routeProvider,$locationProvider) {
	
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
		templateUrl	: "templates/error.html"
	})
	.otherwise({
		templateUrl : "templates/main.html",
		controller	: "mainCtrl"
	});
	
}]);
