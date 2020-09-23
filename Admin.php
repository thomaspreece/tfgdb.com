<?php
$MetaDescription = "";
$MetaKeywords = "";

$PageName = "Admin.php";
include("./Includes/HeaderFunctions.php");
$MetaTitle = "Admin - ".$DOMAINTITLE;
include("./Includes/Header.php");

$tabNumber = 0;
$subtabNumber = 0;

if(ISSET($_GET['Page'])){
	$tabNumber = intval($_GET['Page']);
}

if(ISSET($_GET['SubPage'])){
	$subtabNumber = intval($_GET['SubPage']);
}

?>
<div style='height:5px;'></div>
<div id="admintabs">
	<ul>
		<li><a href="#admintabs-1">Admin Home</a></li>
		<li><a href="#admintabs-2">Latest Data</a></li>
		<?php
		if($FDBPrivilages['MLinks']==2 || $FDBPrivilages['MLinks']==2 || $FDBPrivilages['MReviews']==2 || $FDBPrivilages['MReviews']==2){
			echo "<li><a href=\"#admintabs-3\">Moderation</a></li>";
		}
		?>
		
		<?php
		if($FDBPrivilages['VULog']==2 || $FDBPrivilages['VCLog']==2 || $FDBPrivilages['RGame']==2 || $FDBPrivilages['VMLog']==2){
			echo "<li><a href=\"#admintabs-4\">Logs</a></li>";
		}
		?>
	</ul>
	<div id="admintabs-1">
		<p style='text-align:center;'>Administration Area</p>
	</div>
	
	
	<div id="admintabs-2">
		<?php require($_SERVER['DOCUMENT_ROOT']."/admin/tabs_latest.php");?>
	</div>
	
	<?php 
	if($FDBPrivilages['MLinks']==2 || $FDBPrivilages['MWLinks']==2 || $FDBPrivilages['MResources']==2 || $FDBPrivilages['MReviews']==2){
		echo "<div id=\"admintabs-3\">";
		require($_SERVER['DOCUMENT_ROOT']."/admin/tabs_moderation.php");
		echo "</div>";
	}
	?>
	
	<?php
	if($FDBPrivilages['VULog']==2 || $FDBPrivilages['VMLog']==2 || $FDBPrivilages['RGame']==2 || $FDBPrivilages['VCLog']==2){
		echo "<div id=\"admintabs-4\">";
		require($_SERVER['DOCUMENT_ROOT']."/admin/tabs_logs.php");
		echo "</div>";
	}
	?>
	
	
</div>

	
				
<script>
	$(function() {
		$( "#admintabs" ).tabs({
		active: <?php echo $tabNumber;?>,
		beforeLoad: function( event, ui ) {
			ui.jqXHR.error(function() {
				ui.panel.html("Error loading tab! Please contact admin@tfgdb.com to notify them of this problem. ");
			});
		}
		});
	});	
</script>
				
					
<?php 
include("./Includes/Footer.php");
include("./Includes/FooterFunctions.php");
?>				