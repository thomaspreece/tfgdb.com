<h1>Logs Toolbox</h1>
<div id="logstabs">
	<ul>
		<li><a href="#logstabs-1">Logs Home</a></li>
		<?php 
		if($FDBPrivilages['VULog']==2){
			echo "<li><a href=\"admin/log_users.php\">User</a></li>";
		}
		if($FDBPrivilages['VMLog']==2){
			echo "<li><a href=\"admin/log_moderators.php\">Moderator</a></li>";
		}
		if($FDBPrivilages['RGame']==2){
			echo "<li><a href=\"admin/log_deleted.php\">Deleted Games</a></li>";
		}
		if($FDBPrivilages['VCLog']==2){
			echo "<li><a href=\"admin/log_cron.php\">CRON</a></li>";
		}
		?>
	</ul>
	<div id="logstabs-1">
		<p style='margin:10px;text-align:center;'>Use this tab to view various types of logs that are generated automatically. This allows you to check up what the latest modifications to the database are.</p>
	</div>
</div>

<script>
	$(function() {
		$( "#logstabs" ).tabs({
		<?php 
		if ($tabNumber==3){
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
				case "admin/log_users.php":
					window.history.pushState(null, "Admin", "<?php echo $DOMAIN; ?>/Admin.php?Page=3&SubPage=1");
					break;
				case "admin/log_moderators.php":
					window.history.pushState(null, "Admin", "<?php echo $DOMAIN; ?>/Admin.php?Page=3&SubPage=2");
					break;
				case "admin/log_deleted.php":
					window.history.pushState(null, "Admin", "<?php echo $DOMAIN; ?>/Admin.php?Page=3&SubPage=3");
					break;
				case "admin/log_cron.php":
					window.history.pushState(null, "Admin", "<?php echo $DOMAIN; ?>/Admin.php?Page=3&SubPage=4");
					break;
				
			}
		},
		cache: false,
		select: function(event, ui) {

		}
		});
	});	
</script>