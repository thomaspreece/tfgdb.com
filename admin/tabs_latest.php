<h1>Latest items</h1>
<div id="latesttabs">
	<ul>
		<li><a href="#latesttabs-1">Items Home</a></li>
		<li><a href="admin/latest_games.php">Games</a></li>
		<li><a href="admin/latest_reviews.php">Reviews</a></li>
		<li><a href="admin/latest_ratings.php">Ratings</a></li>
		<li><a href="admin/latest_artwork.php">Artwork</a></li>

	</ul>
	<div id="latesttabs-1">
		<p style='margin:10px;text-align:center;'>Here you can see the latest additions to the database.</p>
	</div>
</div>

<script>
	$(function() {
		$( "#latesttabs" ).tabs({
		<?php 
		if ($tabNumber==1){
			echo "active: ",$subtabNumber.",";
		}
		?>
		beforeLoad: function( event, ui ) {
			ui.jqXHR.error(function() {
				ui.panel.html("Error loading tab! Please contact admin@tfgdb.com to notify them of this problem. ");
			});
			
			 var $panel = $(ui.panel);

			 if ($panel.is(":empty")) {
				 $panel.append("<div class='tab-loading'>Loading...</div>")
			 }				
			switch(ui.ajaxSettings.url){
				case "admin/latest_games.php":
					window.history.pushState(null, "Admin", "<?php echo $DOMAIN; ?>/Admin.php?Page=1&SubPage=1");
					break;
				case "admin/latest_reviews.php":
					window.history.pushState(null, "Admin", "<?php echo $DOMAIN; ?>/Admin.php?Page=1&SubPage=2")
					break;
				case "admin/latest_ratings.php":
					window.history.pushState(null, "Admin", "<?php echo $DOMAIN; ?>/Admin.php?Page=1&SubPage=3")
					break;
				case "admin/latest_artwork.php":
					window.history.pushState(null, "Admin", "<?php echo $DOMAIN; ?>/Admin.php?Page=1&SubPage=4")
					break;
			}
		}
		});
	});	
</script>