<?php
$ip=$_SERVER['REMOTE_ADDR'];


if ($ip=="10.10.10.10"){
include($_SERVER['DOCUMENT_ROOT']."/Includes/HeaderFunctions.php");


	if (isset($_REQUEST["Name"])){
		$Name=filter_var($_REQUEST["Name"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW );
	}else{
		die('Error
No Name');
	}
	if (isset($_REQUEST["Desc"])){
		$Desc=filter_var($_REQUEST["Desc"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW );
	}else{
		die('Error
No Desc');
	}

	if (isset($_REQUEST["Genre"])){
		$Genre=filter_var($_REQUEST["Genre"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW );
	}else{
		die('Error
No Genre');
	}
	if (isset($_REQUEST["Platform"])){
		$Platform=filter_var($_REQUEST["Platform"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW );
	}else{
		die('Error
No Platform');
	}
	if (isset($_REQUEST["Mode"])){
		$Mode=filter_var($_REQUEST["Mode"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW );
	}else{
		die('Error
No Mode');
	}


	if (isset($_REQUEST["Sou"])){
		$Sou=filter_var($_REQUEST["Sou"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW );
	}else{
		die('Error
No Sou');
	}
	if (isset($_REQUEST["Gra"])){
		$Gra=filter_var($_REQUEST["Gra"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW );
	}else{
		die('Error
No Gra');
	}
	if (isset($_REQUEST["Stu"])){
		$Stu=filter_var($_REQUEST["Stu"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW );
	}else{
		die('Error
No Stu');
	}
	if (isset($_REQUEST["Typ"])){
		$Typ=filter_var($_REQUEST["Typ"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW );
	}else{
		die('Error
No Typ');
	}
	if (isset($_REQUEST["Rel"])){
		$Rel=filter_var($_REQUEST["Rel"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW );
	}else{
		die('Error
No Rel');
	}
	if (isset($_REQUEST["Trailer"])){
		$Trailer=filter_var($_REQUEST["Trailer"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW );
		$Trailer = preg_replace('/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=)?/',"",$Trailer);
		$Trailer = preg_replace('/(&.*)?$/',"",$Trailer);
		if(strlen($Trailer)!=11){
			$Trailer = "";
		}
	}else{
		die('Error
No Trailer');
	}


	$stmt = $Gamedb->prepare("SELECT * FROM freegames WHERE LOWER(`Name`)=LOWER(:name)");
	$stmt->bindValue(':name', $_REQUEST['Name'], PDO::PARAM_STR);
	$stmt->execute();
	if($stmt->rowCount()){
		$Gamedb = null;
		echo "Error
Already in database";
		die();
	}

	$stmt = $Gamedb->prepare("SELECT * FROM studio WHERE Studio=:Stu");
	$stmt->bindValue(':Stu', $Stu, PDO::PARAM_STR);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$Stu = $row['ID'];


	$stmt = $Gamedb->prepare("SELECT * FROM graphics WHERE Graphics=:Gra");
	$stmt->bindValue(':Gra', $Gra, PDO::PARAM_STR);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$Gra = $row['ID'];

	$stmt = $Gamedb->prepare("SELECT * FROM source WHERE Source=:Sou");
	$stmt->bindValue(':Sou', $Sou, PDO::PARAM_STR);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$Sou = $row['ID'];


	$stmt = $Gamedb->prepare("SELECT * FROM types WHERE Type=:Typ");
	$stmt->bindValue(':Typ', $Typ, PDO::PARAM_STR);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$Typ = $row['ID'];

	$stmt = $Gamedb->prepare("SELECT * FROM `release` WHERE `Release`= :Rel");
	$stmt->bindValue(':Rel', $Rel, PDO::PARAM_STR);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$Rel = $row['ID'];

	$stmt = $Gamedb->prepare("INSERT INTO `freegames`(`ID`, `Studio`, `Graphics`, `Source`, `Type`, `Name`, `About`, `Trailer`, `Rating`, `RateNum`, `RateTot`, `Release`, `EditedBy`, `CreatedBy`, `PlatformBITS`, `GenreBITS`, `ModeBITS`, `TodaysGame`, `QuedTodaysGame`)
	VALUES (null,:Stu,:Gra,:Sou,:Typ,:Name,:Desc,:Trailer,0,0,0,:Rel,'TodaysGame','TodaysGame',:platform,:genre,:mode,0,1)");
	$stmt->bindValue(':Stu', $Stu, PDO::PARAM_INT);
	$stmt->bindValue(':Gra', $Gra, PDO::PARAM_INT);
	$stmt->bindValue(':Sou', $Sou, PDO::PARAM_INT);
	$stmt->bindValue(':Typ', $Typ, PDO::PARAM_INT);
	$stmt->bindValue(':Name', $Name, PDO::PARAM_STR);
	$stmt->bindValue(':Desc', $Desc, PDO::PARAM_STR);
	$stmt->bindValue(':Trailer', $Trailer, PDO::PARAM_STR);
	$stmt->bindValue(':Rel', $Rel, PDO::PARAM_INT);

	$stmt->bindValue(':platform', $Platform, PDO::PARAM_INT);
	$stmt->bindValue(':genre', $Genre, PDO::PARAM_INT);
	$stmt->bindValue(':mode', $Mode, PDO::PARAM_INT);

	$stmt->execute();
	echo $Gamedb->lastInsertId('ID');
	die();

}else{
	echo "Error
Unauthorised!
	<br/>";
	echo "User: ".$ip."<br/>";
	die('Unauthorised Access: Script Terminated');
}


include($_SERVER['DOCUMENT_ROOT']."/Includes/FooterFunctions.php");

?>
