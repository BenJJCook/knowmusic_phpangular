<?php include 'inc/header.php'; ?>
	<?php		
		require('inc/request.php');
		
		if(!isset($_GET['g'])):
			echo "Lol, wtf did you do";
		else:
		
			$genre = $_GET['g'];
		
			$si = new SessionInfo('API_ID', 'API_SECRET', 'CALLBACK_URI');
			
			if(isset($_SESSION['tokenRetrieved'])){
				$endTime = $_SESSION['tokenEndTime'];
				if (time() > $endTime) {
					$si->requestAccessToken();
				}
			} else {
				$si->requestAccessToken();
			}
			
			$genreCode = '';
			
			if($genre == 'classical') {
				$genreCode = '533zfj4r1QibotYtASNP5S';
			} elseif($genre == 'forties') {
				$genreCode = '1omQHz0FSGPVkfpGHKWRbj';
			}
			
			$jsonPlaylist = $si->requestPlaylist($genreCode);
			?>					
			<main class="container-fluid">
				
				<section class="row">
					<div id="playlist-desc" class="col-xs-12">
						<h2 id="genre-name" class="km-header"></h2>
						<p id="genre-description" class="km-text">
						</p>
					</div>
					<ul id="song-list" class="col-xs-12">
					
					</ul>
				</section>
				
			</main>
			
			<div id="play-underlay"></div>
			<div id="play-overlay">
				<span class="close-overlay">&times;</span>
				
				<div id="track-info-overlay">
					<h4 id="track-name-overlay"></h4>
					<p id= "track-artist-overlay"></p>
				</div>
				<div id="spotify-player">
					
				</div>
			</div>
			
			<script>
				$(document).ready(function(){
					
					var playlistData = <?php echo $jsonPlaylist ?>;
					
					var name = playlistData.name;
					var description = playlistData.description;
					
					$('#genre-name').html(name);
					$('#genre-description').html(description);
					
					var tracks = playlistData.tracks.items;
					
					for(var i = 0; i < tracks.length; i++){
						var artists = tracks[i].track.artists;
						var artistString = "";
						for(var j = 0; j < artists.length; j++){
							artistString += artists[j].name;
							if(j !== artists.length-1) {
								artistString += ", ";
							}
						}
						var place = "<span class='track-place col-xs-1'>" + (i+1) + "</span>";
						var track = "<span class='track-name col-xs-11 col-sm-6'>" + tracks[i].track.name + "</span>";
						var spacer = "<span class='track-spacer col-xs-1 visible-xs'></span>"
						var artist = "<span class='track-artists col-xs-11 col-sm-5'>" + artistString + "</span>";
						
						var trackId = tracks[i].track.id;
						
						var newItem = $("<li id='" + trackId + "' class='track-row row'></li>").append(place, track, spacer, artist); 
						
						$("#song-list").append(newItem);
					}
					
					$(".track-row").click(function(){
						var trackId = $(this).attr('id');
						var chosenTrack = "<a href='https://open.spotify.com/track/" + trackId + "'>" + $(this).find('.track-name').text() + "</a>";
						var chosenArtist = $(this).find('.track-artists').text();
						
						var spotifyPlayer = "<iframe class=\"spotify-frame\" src=\"https://open.spotify.com/embed/track/" + trackId + "?theme=white\" height=\"80\" frameborder=\"0\" allowtransparency=\"true\"></iframe>";
						
						$('#track-name-overlay').append(chosenTrack);
						$('#track-artist-overlay').append("by " + chosenArtist);
						
						$('#spotify-player').append(spotifyPlayer);
						
						$('#play-overlay').slideToggle(true);
						$('#play-underlay').toggle(true);
					});
					
					$(".close-overlay").click(function(){
						$('#play-overlay').toggle(false);
						$('#play-underlay').toggle(false);
						$('#spotify-player').empty();
						$('#track-name-overlay').empty();
						$('#track-artist-overlay').empty();
					});
				});
			</script>
		<?php endif;
	?>
<?php include 'inc/footer.php'; ?>