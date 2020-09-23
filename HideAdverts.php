<?php

$MetaDescription = "TheFreeGamesDB, is a project to collect all the free games from around the internet into one place";
$MetaKeywords = "Free,games,database,mac,windows,linux";

$PageName = "HideAdverts.php";

include("./Includes/HeaderFunctions.php");
$MetaTitle = "Adverts - ".$DOMAINTITLE;
include("./Includes/Header.php");

echo "
<div style='height:25px;'></div>
<div class='news'>
	<div style='margin:10px;'>
	<h3>Adverts</h3>
	The adverts on this site are supporting server running costs.<br/>
	You can hide them by registering an account and gaining 25 points by contributing to the community. To find out more about the point system <a href='".$DOMAIN."/Forum/points.php?mode=info'>click here</a>
	</div>

</div>
";



include("./Includes/Footer.php");
include("./Includes/FooterFunctions.php");
?>