<?php
$ip=$_SERVER['REMOTE_ADDR'];


if ($ip=="10.10.10.10"){
	include($_SERVER['DOCUMENT_ROOT']."/Includes/HeaderFunctions.php");

	if (isset($_REQUEST["ID"])){
		$ID=filter_var($_REQUEST["ID"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW );
	}else{
		die('Error
No ID');
	}
	if (isset($_REQUEST["Platform"])){
		$Platform=filter_var($_REQUEST["Platform"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW );
	}else{
		die('Error
No Platform');
	}
	if (isset($_REQUEST["OS"])){
		$OS=$_REQUEST["OS"];
	}else{
		die('Error
No OS');
	}
	if (isset($_REQUEST["Direct"])){
		$Direct=$_REQUEST["Direct"];
	}else{
		die('Error
No Direct');
	}
	if (isset($_REQUEST["Link"])){
		$Link=$_REQUEST["Link"];
	}else{
		die('Error
No Link');
	}
	if (isset($_REQUEST["Version"])){
		$Version=$_REQUEST["Version"];
	}else{
		die('Error
No Version');
	}
	if (isset($_REQUEST["Size"])){
		$Size=$_REQUEST["Size"];
	}else{
		die('Error
No Size');
	}


	$stmt = $Gamedb->prepare("SELECT * FROM freegames WHERE `ID`=:id");
	$stmt->bindValue(':id', $ID, PDO::PARAM_STR);
	$stmt->execute();
	if(($stmt->rowCount())==0){
		$db = null;
		echo "Error
No ID - ID Wrong";
		die();
	}

	$stmt = $Gamedb->prepare("INSERT INTO `downloadsbits`(`DownloadID`, `ID`, `OS`, `Version`, `Platform`, `FileSize`, `Download`, `Direct`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy`)
	VALUES (null,:id,:os,:version,:platform,:filesize,:download,:direct,'0','0','','QuedTodaysGame')");
	$stmt->bindValue(':id', $ID, PDO::PARAM_INT);
	$stmt->bindValue(':os', $OS, PDO::PARAM_STR);
	$stmt->bindValue(':version', $Version, PDO::PARAM_STR);
	$stmt->bindValue(':platform', $Platform, PDO::PARAM_INT);
	$stmt->bindValue(':filesize', $Size, PDO::PARAM_STR);
	$stmt->bindValue(':download', $Link, PDO::PARAM_STR);
	$stmt->bindValue(':direct', $Direct, PDO::PARAM_STR);

	$stmt->execute();

	include($_SERVER['DOCUMENT_ROOT']."/Includes/FooterFunctions.php");
	die();

}else{
	echo "Error
Unauthorised!
	<br/>";
	echo "User: ".$ip."<br/>";
	die('Unauthorised Access: Script Terminated');
}




?>
