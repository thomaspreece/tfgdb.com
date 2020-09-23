<h1>Game Moderator Toolbox</h1>
<div id="moderationtabs">
	<ul>
		<li><a href="#moderationtabs-1">Moderation Home</a></li>
		<?php
		if($FDBPrivilages['MWLinks']==2){
			$stmt = $Gamedb->prepare("SELECT * FROM websites WHERE `Pending`=1 OR `Flagged`=1");
			$stmt->execute();	
			if($stmt->rowCount()==0){
				//echo "<li><a href=\"admin/pending_websites.php\">Websites</a></li>";
			}else{
				echo "<li><a href=\"admin/pending_websites.php\">Websites (Items Pending)</a></li>";
			}
		}
		if($FDBPrivilages['MLinks']==2){
			$stmt = $Gamedb->prepare("SELECT * FROM downloadsbits WHERE `Pending`=1 OR `Flagged`=1");
			$stmt->execute();	
			if($stmt->rowCount()==0){
				//echo "<li><a href=\"admin/pending_links.php\">Links</a></li>";
			}else{
				echo "<li><a href=\"admin/pending_links.php\">Links (Items Pending)</a></li>";
			}
		}
		if($FDBPrivilages['MReviews']==2){
			$stmt = $Gamedb->prepare("SELECT * FROM reviews WHERE `Pending`=1 OR `Flagged`=1");
			$stmt->execute();	
			if($stmt->rowCount()==0){
				//echo "<li><a href=\"admin/pending_reviews.php\">Reviews</a></li>";
			}else{
				echo "<li><a href=\"admin/pending_reviews.php\">Reviews (Items Pending)</a></li>";
			}
		}
		if($FDBPrivilages['MResources']==2){
			$stmt = $Gamedb->prepare("SELECT * FROM resources WHERE `Pending`=1 OR `Flagged`=1");
			$stmt->execute();	
			if($stmt->rowCount()==0){
				//echo "<li><a href=\"admin/pending_resources.php\">Resources</a></li>";
			}else{
				echo "<li><a href=\"admin/pending_resources.php\">Resources (Items Pending)</a></li>";
			}
		}
		?>
	</ul>
	<div id="moderationtabs-1">
		<?php
		echo "
		<p style='margin:10px;text-align:center;'>From here you can approve various items that users have added to the database.</p>
		<table class='LogTable'>
		<tr>
			<td>Pending Websites: </td>
			<td>";
			$stmt = $Gamedb->prepare("SELECT * FROM websites WHERE `Pending`=1 OR `Flagged`=1");
			$stmt->execute();	
			echo $stmt->rowCount();			
		echo "
			</td>
		</tr>
		<tr>
			<td>Pending Links: </td>
			<td>";
		$stmt = $Gamedb->prepare("SELECT * FROM downloadsbits WHERE `Pending`=1 OR `Flagged`=1");
		$stmt->execute();	
		echo $stmt->rowCount();		
		echo "		
			</td>
		</tr>
		<tr>
			<td>Pending Reviews: </td>
			<td>";
		$stmt = $Gamedb->prepare("SELECT * FROM reviews WHERE `Pending`=1 OR `Flagged`=1");
		$stmt->execute();	
		echo $stmt->rowCount();		
		echo "			
			</td>
		</tr>
		<tr>
			<td>Pending Resources: </td>
			<td>";
		$stmt = $Gamedb->prepare("SELECT * FROM resources WHERE `Pending`=1 OR `Flagged`=1");
		$stmt->execute();	
		echo $stmt->rowCount();
		echo "			
			</td>
		</tr>
		</table>
		";
		?>
	</div>
</div>

<script>
	$(function() {
		$( "#moderationtabs" ).tabs({
		<?php 
		if ($tabNumber==2){
			echo "active: ",$subtabNumber.",";
		}
		?>
		beforeLoad: function( event, ui ) {
			 var $panel = $(ui.panel);

			 if ($panel.is(":empty")) {
				 $panel.append("<div class='tab-loading'>Loading...</div>")
			 }		
			ui.jqXHR.error(function() {
				ui.panel.html("Error loading tab! Please contact admin@tfgdb.com to notify them of this problem. ");
			});
			switch(ui.ajaxSettings.url){
				case "admin/pending_websites.php":
					window.history.pushState(null, "Admin", "<?php echo $DOMAIN; ?>/Admin.php?Page=2&SubPage=1");
					break;
				case "admin/pending_links.php":
					window.history.pushState(null, "Admin", "<?php echo $DOMAIN; ?>/Admin.php?Page=2&SubPage=2");
					break;
				case "admin/pending_reviews.php":
					window.history.pushState(null, "Admin", "<?php echo $DOMAIN; ?>/Admin.php?Page=2&SubPage=3");
					break;
				case "admin/pending_resources.php":
					window.history.pushState(null, "Admin", "<?php echo $DOMAIN; ?>/Admin.php?Page=2&SubPage=4");
					break;
				
			}
		},
		cache: false,
		select: function(event, ui) {

		}
		});
	});	
</script>
