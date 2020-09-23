<?php
$MetaDescription = "TFGdb.com the new home of thefreegamesdb.com. The Free Games Database(TFGdb) is a large database of free games with direct downloads to each game";
$MetaKeywords = "Free,games,database,mac,windows,linux,genres,3D,2D,Hot Picks,Random Game,Top Rated";
$MetaTitle = "TheFreeGamesDB";
$PageName = "index.php";

include("./Includes/HeaderFunctions.php");
$MetaTitle = $DOMAINTITLE;
include("./Includes/Header.php");

$stmt = $Gamedb->prepare("SELECT * FROM `freegames` WHERE `TodaysGame`=1 LIMIT 0,1");
$stmt->execute();	
if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {					
	$ResMid = "";
	$NoRes = true;
	$b = 4;
	do{
		$stmt2 = $Gamedb->prepare("SELECT * FROM resources WHERE ID=:id AND Type=:num");
		$stmt2->bindValue(':id', $row['ID'], PDO::PARAM_INT);
		$stmt2->bindValue(':num', $b, PDO::PARAM_INT);
		$stmt2->execute();							
	
		if($Resrow = $stmt2->fetch(PDO::FETCH_ASSOC)){
			$ResMid = $Resrow['ResourcePath'];
			$NoRes = false;
		}
		switch($b){
			case 1: 
				$NoRes = false;
				break;
			case 3:
				$b = 1;
				break;
			case 4:
				$b = 3;
				break;
		}
	}while($NoRes == true);

	if ($ResMid==null or $ResMid==""){
		$ResMid = "NoArtwork/NoShot.png";
	}

	$MaxLen = 400;
	
	if(strlen($row['About']) > $MaxLen){
		$CropNumber = 0;
		for($i=$MaxLen;$i>0;$i--){
			if(substr($row['About'],$i,1)=="."){
				$CropNumber = $i;
				break;
			}
		}
		if($CropNumber == 0){
			for($i=$MaxLen;$i>0;$i--){
				if(substr($row['About'],$i,1)==" "){
					$CropNumber = $i;
					break;
				}
			}
		}
		if($CropNumber == 0){
			$CropNumber = $MaxLen;
		}
		$row['About'] = substr($row['About'],0,$CropNumber)."...";
	}

	
	$TodaysGameText = "<div class='HomeSliderTitleDiv'><a style='text-decoration:none;' href='".$DOMAIN."/Games/".$row['ID']."/".$row['Name']."'>".$row['Name']."</a></div>
	".$row['About']."
	";
	
	$TodaysGamePic = artwork_correct($ResMid);
	$TodaysGameHref = $DOMAIN."/Games/".$row['ID']."/".$row['Name'];
}
		
?>
		
<div style='height:40px;'></div>
<div id="CenterImageSlider" class="slidorion">
	<div class="slider">
		<div class="slide" style='background:white;'><a href='http://photongamemanager.com'><iframe width="590" height="400" src="//www.youtube.com/embed/oKSDIxzU7f8?wmode=transparent" frameborder="0" allowfullscreen></iframe></a></div>		
		<div class="slide" style='background:white;'><a href='<?php echo $TodaysGameHref; ?>'><img width='600px' src="<?php echo $TodaysGamePic; ?>" /></a></div>
		<div class="slide"><a href='<?php echo $DOMAIN; ?>/Forum/'><img width='600px' src="Resources/Moderator.jpg" /></a></div>
		<div class="slide"><a href='https://www.facebook.com/pages/The-Free-Games-Database-tfgdbcom/122542944583665'><img width='600px' src="Resources/Facebook2.jpg" /></a></div>
		<div class="slide"><a href='<?php echo $DOMAIN; ?>/Forum/points.php?mode=info'><img width='600px' src="Resources/Present.jpg" /></a></div>
	</div>

	<div class="accordion">

		<div class="header">Photon Game Manager V4</div>
		<div class="content"><div class='HomeSliderTitleDiv'><a style='text-decoration:none;'  href='http://photongamemanager.com/'>More Details/Download</a></div>Photon Game Manager is the latest work from the team that brought you TFGdb, it is an advanced game launcher that is designed to automate the process of downloading game fan art, box art, screenshots, game info, official patches and organize the data in a functional and sleek interface, allowing you to browse, sort, filter, patch, mount and run your games.</div>
		
		<div class="header">This Weeks New Game</div>
		<div class="content"><?php echo $TodaysGameText; ?></div>
		<div class="header">We Need Moderators</div>
		<div class="content"><div class='HomeSliderTitleDiv'><a style='text-decoration:none;'  href='<?php echo $DOMAIN; ?>/Forum/'>Apply</a></div>With the ever increasing amount of game links we need some more moderators to fix broken links and approve the new ones. If you are interested please apply on the forum.</div>
		<div class="header">We have a facebook page!</div>
		<div class="content"><div class='HomeSliderTitleDiv'><a style='text-decoration:none;'  href='https://www.facebook.com/pages/The-Free-Games-Database-tfgdbcom/122542944583665'>Like Us</a></div>Like us on facebook to get updates about the site and our favourite free games straight to your news feed.</div>
		<div class="header">Points = Prizes</div>
		<div class="content"><div class='HomeSliderTitleDiv'><a style='text-decoration:none;' href='<?php echo $DOMAIN; ?>/Forum/points.php?mode=info'>Points</a></div> Points are a form of currency used on this website. Each time you contribute something to this community you earn a certain number of points. You can then spend those points on items in our shop, such as games and software. Click the link to find out more.</div>
	</div>
</div>
<br/>

<?php include("./Includes/GamesMosaic.php"); ?>	


<?php 
	include("./Includes/Footer.php"); 
	include("./Includes/FooterFunctions.php");
?>
