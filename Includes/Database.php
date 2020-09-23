<?php

include("./DatabaseSettings.php");

try {
    $Gamedb = new PDO('mysql:host=localhost;dbname='.$DB_NAME.';', $DB_USER, $DB_PASS);
    $Gamedb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$Gamedb->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $e) {
	error_log("Database Login Failed(MainSite): ".$e->getMessage() , 1, "admin@photongamemanager.com");

    echo 'Error Connecting to Database. Please try website in a few hours';
	die();
}

$stmt = $Gamedb->prepare("SELECT * FROM freegames");
$stmt->execute();
$GAMECOUNT = $stmt->rowCount();

$stmt = $Gamedb->prepare("SELECT * FROM genresbits");
$stmt->execute();
$GAMEGENRESBITS = array();
while($temp = $stmt->fetch(PDO::FETCH_ASSOC)){
	$GAMEGENRESBITS[] = $temp;
}

switch ($PageName) {
	case "API-Search.php":
	case "API-GetGame.php":
	case "GetGame.php":
	case "EditGame.php":
	case "Admin.php":
	case "pending_links.php":
	case "GameScript.php":
	case "Games.php":
	case "AddGame.php":

		$stmt = $Gamedb->prepare("SELECT * FROM modebits");
		$stmt->execute();
		$GAMEMODESBITS = array();
		while($temp = $stmt->fetch(PDO::FETCH_ASSOC)){
			$GAMEMODESBITS[] = $temp;
		}

		$stmt = $Gamedb->prepare("SELECT * FROM age");
		$stmt->execute();
		$GAMEAGES = array();
		while($temp = $stmt->fetch(PDO::FETCH_ASSOC)){
			$GAMEAGES[] = $temp;
		}

		$stmt = $Gamedb->prepare("SELECT * FROM platformbits");
		$stmt->execute();
		$GAMEPLATFORMSBITS = array();
		while($temp = $stmt->fetch(PDO::FETCH_ASSOC)){
			$GAMEPLATFORMSBITS[] = $temp;
		}

		$stmt = $Gamedb->prepare("SELECT * FROM source");
		$stmt->execute();
		$GAMESOURCES = array();
		while($temp = $stmt->fetch(PDO::FETCH_ASSOC)){
			$GAMESOURCES[] = $temp;
		}

		$stmt = $Gamedb->prepare("SELECT * FROM graphics");
		$stmt->execute();
		$GAMEGRAPHICS = array();
		while($temp = $stmt->fetch(PDO::FETCH_ASSOC)){
			$GAMEGRAPHICS[] = $temp;
		}

		$stmt = $Gamedb->prepare("SELECT * FROM studio");
		$stmt->execute();
		$GAMESTUDIOS = array();
		while($temp = $stmt->fetch(PDO::FETCH_ASSOC)){
			$GAMESTUDIOS[] = $temp;
		}

		$stmt = $Gamedb->prepare("SELECT * FROM types");
		$stmt->execute();
		$GAMETYPES = array();
		while($temp = $stmt->fetch(PDO::FETCH_ASSOC)){
			$GAMETYPES[] = $temp;
		}

		$stmt = $Gamedb->prepare("SELECT * FROM `release`");
		$stmt->execute();
		$GAMERELEASE = array();
		while($temp = $stmt->fetch(PDO::FETCH_ASSOC)){
			$GAMERELEASE[] = $temp;
		}
	break;

}

?>
