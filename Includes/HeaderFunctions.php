<?php
require_once($_SERVER['DOCUMENT_ROOT']."/Includes/UsersInclude.php");

$DOMAIN = "http://tfgdb.com";
$DOMAINTITLE = "TFGdb.com: a large database of downloadable and online free to play games";

if($user->data['is_registered']){
	if($FDBPrivilages["VAPanel"]==2){
		//Continue
	}else{
		switch ($PageName) {
			Case "Admin.php":
				header( "Location: ".$DOMAIN."/index.php" );
				die('');
				break;
		}	
	}
}else{
	switch ($PageName) {
		Case "Admin.php":
		case "AddGame.php":
		case "EditGame.php":
			header( "Location: ".$DOMAIN."/Login.php?Redirect=".$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"] );
			die('');
			break;
		default:
			break;
	}
}		


//Load Functions
require_once($_SERVER['DOCUMENT_ROOT']."/Includes/functions.php");

//Load Database
require_once($_SERVER['DOCUMENT_ROOT']."/Includes/Database.php");

?>