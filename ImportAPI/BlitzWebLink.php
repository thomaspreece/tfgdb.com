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
	if (isset($_REQUEST["Website"])){
		$Website=filter_var($_REQUEST["Website"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW );
	}else{
		die('Error
No Website');
	}
	if (isset($_REQUEST["Notes"])){
		$Notes=filter_var($_REQUEST["Notes"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW );
	}else{
		die('Error
No Notes');
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

	$stmt = $Gamedb->prepare("INSERT INTO `websites`(`WebsiteID`, `ID`, `Website`, `Notes`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy`) VALUES (null,:id,:website,:notes,0,0,'','QuedTodaysGame')");
	$stmt->bindValue(':id', $ID, PDO::PARAM_INT);
	$stmt->bindValue(':website', $Website, PDO::PARAM_STR);
	$stmt->bindValue(':notes', $Notes, PDO::PARAM_STR);
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
