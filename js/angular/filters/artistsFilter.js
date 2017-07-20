/* ---- Artists Formatting Filter ---- */
app.filter('artists', [function() {
	
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
  
}]);