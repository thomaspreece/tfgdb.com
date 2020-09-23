<?php
$MetaDescription = "";
$MetaKeywords = "";

$PageName = "404.php";

include("./Includes/HeaderFunctions.php");
$MetaTitle = $DOMAINTITLE;
include("./Includes/Header.php");

?>
<div class='news'>
	<h3>500 Internal Server Error</h3>
	<p class='center'>
	You Requested: <b>http://<?php 
	echo $_SERVER['HTTP_HOST'];
	echo $_SERVER['REQUEST_URI'];?></b><br/>						
	But the server seems to have been unable to process that page in some way. So why not try one of the games below instead.			
	</p>
</div>
<?php include("./Includes/GamesSliders.php"); ?>
		
<?php 
	include("./Includes/Footer.php"); 
	include("./Includes/FooterFunctions.php");
?>