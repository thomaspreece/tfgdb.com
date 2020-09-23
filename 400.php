<?php
$MetaDescription = "";
$MetaKeywords = "";
$PageName = "400.php";


include("./Includes/HeaderFunctions.php");

$MetaTitle = $DOMAINTITLE;

include("./Includes/Header.php");



?>

<div class='news'>
	<h3>400 Bad Request Error</h3>
	<p class='center'>
	You Requested: <b>http://<?php 
	echo $_SERVER['HTTP_HOST'];
	echo $_SERVER['REQUEST_URI'];?></b><br/>						
	But the server doesnt know how to process this request. So why not try one of the games below instead.						
	</p>
</div>
<?php include("./Includes/GamesSliders.php"); ?>
			
<?php 
	include("./Includes/Footer.php"); 
	include("./Includes/FooterFunctions.php");
?>