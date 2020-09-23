<?php
$MetaDescription = "All the latest game bundle deals from around the internet";
$MetaKeywords = "Games, Bundles, free, games";

$PageName = "Bundles.php";

include("./Includes/HeaderFunctions.php");
$MetaTitle = "Game Bundles - ".$DOMAINTITLE;
include("./Includes/Header.php");
?>

<script type='text/javascript'>
	$(function() {
        $( "#accordion" ).accordion({
		active: false,
		collapsible: true
		});
    });	
</script>
<div class='news'>
<?php
if($_GET["Error"]=='AddFail'){
echo "<div class='center' style=\"color: #FF0000;\" >
Bundle Add Failed
</div>";
}		
if($_GET["Error"]=='AddModQueue'){
echo "<div class='center' style=\"color: #004400;\" >
Bundle Submitted for Moderation
</div>";
}	
if($_GET["Error"]=='AddSuccess'){
echo "<div class='center' style=\"color: #004400;\" >
Bundle Added Successfully 
</div>";
}	
?>					
					
<!--<h1>Bundles</h1>-->

<h2>Popular Bundles</h2>						
<div style='width:780px;margin-right:auto;margin-left:auto;'>
<a href='http://www.humblebundle.com/'><img src='Resources/Bundles/Humble.png' width='150px' alt='Humble Bundle' /></a>
<a href='http://www.indiegala.com/'><img src='Resources/Bundles/IndieGala.png' width='150px' alt='IndieGala Bundle' /></a>
<a href='http://www.indieroyale.com/'><img src='Resources/Bundles/IndieRoyale.png' width='150px' alt='IndieRoyale Bundle' /></a>
<a href='http://groupees.com/'><img src='Resources/Bundles/Groupees.png' width='150px' alt='Groupees Bundle' /></a>
<a href='http://bundle-in-a-box.com/'><img src='Resources/Bundles/BiaB.png' width='150px' alt='Bundle in a Box' /></a>
</div>

<?php						
/*
echo "	
<h2>Current Bundles</h2>							
<div style='width: 500px;margin-left: auto;margin-right: auto ;'>					
";

$stmt = $Gamedb->prepare("SELECT * FROM bundles WHERE `Pending`=0");
$stmt->execute();

$i = 0;
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
echo "
<p>
	<div style='text-align:center;'><b>".$row['Title']."</b> ";
		if($FDBPrivilages['DBundles'] == 2){
			echo "<a href='AddBundle.php?ID=".$row['ID']."&Request=Del'>(Delete)</a>";
		}
	echo "
	</div><br/>
	".$row['About']."<br/><br/>
	<b>Price:</b> ".$row['Price']."<br/>
	<b>Link:</b> <a href='".$row['Website']."'>".$row['Website']."</a>
	<div style='margin:10px;width:500px;margin-left: auto;margin-right: auto ;'>
	<div style='height:40px;' id=\"Countdown".$i."\"></div>
	<script type=\"text/javascript\">
	$(function () {
		var Day".$i." = new Date();
		Day".$i." = new Date(".substr($row['Expires'],0,4).", ".substr($row['Expires'],5,2)." - 1, ".substr($row['Expires'],8,2)." , ".substr($row['Expires'],11,2)." , ".substr($row['Expires'],14,2)." , ".substr($row['Expires'],17,2).");
		$('#Countdown".$i."').countdown({until: Day".$i.", format: 'DHM'});
	});
	</script>	
	<noscript>
	Ends: ".$row['Expires']."
	</noscript>
	</div>
	<hr/>
</p>
";
$i = $i + 1;

}
echo "</div>";

if($FDBPrivilages['ABundles'] == 2){
	echo "
	
<div id=\"accordion\">
	<h3>Submit A Bundle</h3>
	<div>
	<div class='news'>
		<div style='margin-bottom:10px;' class='center'>
			<form action='AddBundle.php' method='post'>
				<table width='100%'>
				<colgroup width='100px'></colgroup>
				<colgroup width='*'></colgroup>
				<tr>
				<td>Name:</td>
				<td><input style='width:100%;' type='text' name='Name' value='' /></td>
				</tr>
				<tr>
				<td>About:</td>
				<td><textarea style='width:100%;Height:100px;' maxlength='500' name='About' title='What games are in bundle?'></textarea></td>
				</tr>
				<tr>
				<td>Price:</td>
				<td><input style='width:100%;' type='text' name='Price' value='' title=\"Enter 'Pay What You Want' or a set price\" /></td>
				</tr>
				<tr>
				<td>Link:</td>
				<td><input style='width:100%;' type='text' name='Link' value='' /></td>
				</tr>
				<tr>
				<td>Expires:</td>
				<td><input style='width:100%;' type='text' name='Expires' value='2012-12-30 12:00:00' title='Must be in format: YYYY-MM-DD HH:MM:SS' /></td>
				</tr>				
				</table>
				<div style='width:180px;margin-left: auto;margin-right: auto;'>
				<input style='width:180px;' type='submit' value='Submit Bundle' />	
				</div>
			</form>
		</div>
	</div>
	</div>
</div>
";	


}

*/													
																								
include("./Includes/Footer.php");
include("./Includes/FooterFunctions.php");
?>