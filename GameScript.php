<?php
$PageName = "GameScript.php";
include("./Includes/HeaderFunctions.php");


//Cleaning Request Values
$_CLEANREQUEST = array();
foreach($_REQUEST as $key => $value){
	switch($key){
		case "Website":
		case "Down":
			if(!filter_var($value, FILTER_VALIDATE_URL)){
				$value = "";
			}
			break;
		case "Action":
			$value = preg_replace('/[^a-zA-Z]/',"",$value);
			$_CLEANREQUEST[$key] = $value;
			break;		
		case "File":
			$value = preg_replace('/[^0-9\.]/',"",$value);
			$_CLEANREQUEST[$key] = $value;
			break;
		case "Platform":
		case "Direct":
			$value = preg_replace('/[^0-9]/',"",$value);
			$_CLEANREQUEST[$key] = $value;
			break;
		case "ID":
		case "Type":
		case "Studio":
		case "Source":
		case "Graphics":
		case "Release":
			$value = preg_replace('/[^0-9]/',"",$value);
			if($value>0){
				$_CLEANREQUEST[$key] = $value;
			}
			break;
		case "genres":
			if(!empty($value)){
				$N = count($value);
				$value2 = 0;
				for($i=0; $i < $N; $i++)
				{
					$value2 = $value2 + (int)$value[$i];
				}
				if($value2 > 0){
					$_CLEANREQUEST[$key] = $value2;
				}
			}			
			break;
		case "modes":
			if(!empty($value)){
				$N = count($value);
				$value2 = 0;
				for($i=0; $i < $N; $i++)
				{
					$value2 = $value2 + $value[$i];
				}
				if($value2 > 0){
					$_CLEANREQUEST[$key] = $value2;
				}
			}			
			break;			
		case "platforms":
			if(!empty($value)){
				$N = count($value);
				$value2 = 0;
				for($i=0; $i < $N; $i++)
				{
					$value2 = $value2 + $value[$i];
				}
				if($value2 > 0){
					$_CLEANREQUEST[$key] = $value2;
				}
			}			
			break;
		case "Trail":
			$value = preg_replace('/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=)?/',"",$value);
			$value = preg_replace('/(&.*)?$/',"",$value);
			if(strlen($value)!=11){
				$value = "";
			}
			$_CLEANREQUEST[$key] = $value;
			break;
		case "Name":
			$value = preg_replace('/[’]/',"'",$value);			
			$value = preg_replace('/[^a-zA-Z0-9 \.\'&:\(\)-]/',"",$value);
			$value = preg_replace('/[\']/',"&#039;",$value);	
			$value = preg_replace('/[&][^#]/',"&amp; ",$value);	
			if(strlen($value)>0){
				$_CLEANREQUEST[$key] = $value;
			}
			break;
		case "Rate":
			$value = preg_replace('/[^0-9]/',"",$value);
			if($value > 10 || $value < 0){
				
			}else{
				$_CLEANREQUEST[$key] = $value;
			}
			break;
		
		case "Pros":
		case "Cons":
			$value = preg_replace('/<br\/>/',"\n",$value);
			$value = preg_replace('/[^a-zA-Z0-9\., \n\'\"’\(\)&\?!\+-:;]/',"",$value);
			$value = preg_replace('/[\n]/',"<br/>",$value);
			$_CLEANREQUEST[$key] = $value;
			break;
		case "About":
		case "Review":
			$value = preg_replace('/<br\/>/',"\n",$value);
			$value = preg_replace('/[^a-zA-Z0-9\., \n\'\"’\(\)&\?!\+-:;]/',"",$value);
			$value = preg_replace('/[\n]/',"<br/>",$value);
			$value = preg_replace('/[&][^#]/',"&amp; ",$value);	
			if(strlen($value)>0){
				$_CLEANREQUEST[$key] = $value;
			}
			break;
		case "Notes":
		case "OS":
		case "Ver":
			$value = preg_replace('/[^a-zA-Z0-9\., \'\"’\(\)&\?!]/',"",$value);
			$_CLEANREQUEST[$key] = $value;
			break;
		default:
			$_CLEANREQUEST[addslashes(htmlspecialchars($key,ENT_QUOTES))] = addslashes(htmlspecialchars($value,ENT_QUOTES));
			break;
	}
}



if(isset($_CLEANREQUEST['Action']) && isset($_CLEANREQUEST['Protocol'])){
	if($_CLEANREQUEST['Protocol']=="AJAX" || ($_CLEANREQUEST['Protocol']=="POST" && isset($_CLEANREQUEST['Return']) && isset($_CLEANREQUEST['EReturn']))){
	
	if($_CLEANREQUEST['Protocol']=="AJAX"){
		header('Content-Type: application/json');
	}
	
	switch($_CLEANREQUEST['Action']){
		case "AddResource":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['AResources']!=0 && isset($_CLEANREQUEST['ID']) && isset($_CLEANREQUEST['ResType'])){


					define ("MAX_SIZE","1500"); 
					 
					//This function reads the extension of the file. It is used to determine if the
					// file  is an image by checking the extension.
					 function getExtension($str) {
							 $i = strrpos($str,".");
							 if (!$i) { return ""; }
							 $l = strlen($str) - $i;
							 $ext = substr($str,$i+1,$l);
							 return $ext;
					 }

					//This variable is used as a flag. The value is initialized with 0 (meaning no 
					// error  found)  
					//and it will be changed to 1 if an errro occures.  
					//If the error occures the file will not be uploaded.
					 $errors=0;
							
				
					$ID = $_CLEANREQUEST['ID'];
					$ResType = $_CLEANREQUEST['ResType'];
					

					//reads the name of the file the user submitted for uploading
					$image=$_FILES['image']['name'];
					//if it is not empty
					if ($image && $image!="" && $image!=" "){
						//get the original name of the file from the clients machine
						$filename = stripslashes($_FILES['image']['name']);
						//get the extension of the file in a lower case format
						$extension = getExtension($filename);
						$extension = strtolower($extension);
						//if it is not a known extension, we will suppose it is an error and 
						// will not  upload the file,  
						//otherwise we will do more tests
						if ($extension != "jpg" && $extension != "png" && $extension != "jpeg" && $extension != "gif"){
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
							die('');
						}else{
							//get the size of the image in bytes
							//$_FILES['image']['tmp_name'] is the temporary filename of the file
							//in which the uploaded file was stored on the server
							$size=filesize($_FILES['image']['tmp_name']);
							list($width, $height) = getimagesize($_FILES['image']['tmp_name']);
							
							if($ResType==3){
								if(($width==1920 && $height==1080) || ($width==1280 && $height==720)){
									
								}else{
									header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
									die('');
								}
							}
							if($ResType==5){
								if($width==760 && $height==140){
								
								}else{
									header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
									die('');
								}
							}
							//compare the size with the maxim size we defined and print error if bigger
							if ($size > MAX_SIZE*1024){
								header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
								die('');
							}
						}
					}else{
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');
					}
					
					$A_time=time();
					$A_Name="Artwork/".$A_time.'.jpg';
					$A_ThumbName="ArtworkThumb/".$A_time.'.jpg';
					$A_MidName="ArtworkMid/".$A_time.'.jpg';

					$image = new SimpleImage();
					$saved = 0;
					
					$image->load($_FILES['image']['tmp_name']);
					$saved = $saved + $image->save($A_Name,IMAGETYPE_JPEG);						
					
					$image->load($A_Name);
					$image->resizeToWidth(200);
					$saved = $saved + $image->save($A_ThumbName,IMAGETYPE_JPEG);	
				   
					$image->load($A_Name);
					$image->resizeToHeight(350);
					$saved = $saved + $image->save($A_MidName,IMAGETYPE_JPEG);	  

					//If no errors registred, update database
					if($saved == 3){
						
					
						
						$stmt = $Gamedb->prepare("INSERT INTO `resources`(`ID`, `ResNumber`, `ResourcePath`, `ResThumb`, `ResMid`, `Type`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy`) VALUES (:id , Null , :res , :resthumb , :resmid , :restype , :pending , 0 , '' , :username )");
						$stmt->bindValue(':id', $ID, PDO::PARAM_INT);
						$stmt->bindValue(':res', $A_Name, PDO::PARAM_INT);
						$stmt->bindValue(':resthumb', $A_ThumbName, PDO::PARAM_INT);
						$stmt->bindValue(':resmid', $A_MidName, PDO::PARAM_INT);
						$stmt->bindValue(':restype', $ResType, PDO::PARAM_INT);
						$stmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
						if($FDBPrivilages['AResources']==2){
							$stmt->bindValue(':pending', 0, PDO::PARAM_INT);
						}else{
							$stmt->bindValue(':pending', 1, PDO::PARAM_INT);
						}
						$stmt->execute();
						
						$logstmt = $Gamedb->prepare("INSERT INTO `userlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
						$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
						$logstmt->bindValue(':link', $A_Name, PDO::PARAM_STR);
						$logstmt->bindValue(':id', $Gamedb->lastInsertId(), PDO::PARAM_INT);
						$logstmt->bindValue(':gameid', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
						$logstmt->execute();
						
						
						if($ResType==4){
							add_points($user->data['user_id'],$ScreenShotPoints);
						}else{
							add_points($user->data['user_id'],$FanArtPoints);
						}
						
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
						die('');
					}else{
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');
					}

				}else{
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');
				}
			}
		break;	
//------------------------------------------------------------------------------------------------------------------------------
		case "RestoreResource":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['RResources']==2 && isset($_CLEANREQUEST['AutoID'])){
					$stmt = $Gamedb->prepare("INSERT INTO `resources`(`ID`, `ResNumber`, `ResourcePath`, `ResThumb`, `ResMid`, `Type`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy`) SELECT `ID`, `ResNumber`, `ResourcePath`, `ResThumb`, `ResMid`, `Type`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy` FROM `resources_bak` WHERE `AutoID`=:autoid");
					$stmt->bindValue(':autoid', $_CLEANREQUEST['AutoID'], PDO::PARAM_INT);
					$stmt->execute();
					
					if($stmt->rowCount()){		
						$stmt = $Gamedb->prepare("SELECT * FROM `resources_bak` WHERE `AutoID`=:autoid");
						$stmt->bindValue(':autoid', $_CLEANREQUEST['AutoID'], PDO::PARAM_INT);
						$stmt->execute();
						
						if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							$logstmt = $Gamedb->prepare("INSERT INTO `modlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
							$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
							$logstmt->bindValue(':link', $row['ResourcePath'], PDO::PARAM_STR);
							$logstmt->bindValue(':id', $row['ResNumber'], PDO::PARAM_INT);
							$logstmt->bindValue(':gameid', $row['ID'], PDO::PARAM_INT);
							$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
							$logstmt->execute();
						}			
						
						$stmt = $Gamedb->prepare("DELETE FROM `resources_bak` WHERE `AutoID`=:autoid");
						$stmt->bindValue(':autoid', $_CLEANREQUEST['AutoID'], PDO::PARAM_INT);
						$stmt->execute();
						
						if($stmt->rowCount()){																					
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
							die('');		
						}else{
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
							die('');				
						}
					}else{
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');				
					}					
				}else{
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');					
				}
			}
		break;
//------------------------------------------------------------------------------------------------------------------------------		
		case "DeleteResource":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['DResources']==2 && isset($_CLEANREQUEST['ResID'])){
		
					$stmt = $Gamedb->prepare("SELECT * from resources WHERE `ResNumber`=:resid");
					$stmt->bindValue(':resid', $_CLEANREQUEST['ResID'], PDO::PARAM_INT);
					$stmt->execute();
					$temp = $stmt->fetch(PDO::FETCH_ASSOC);					
					
					$id = $temp['ID'];
					$usernameArray = array($temp['By']);
					$useridArray = array();
							
					user_get_id_name($useridArray,$usernameArray);
							
					if($useridArray[0]!="NO_USERS" && $useridArray[0]!=""){
						if($row['Type']==4){
							substract_points($useridArray[0],$ScreenShotPoints);
						}else{
							substract_points($useridArray[0],$FanArtPoints);
						}
					}
			
					$stmt = $Gamedb->prepare("INSERT INTO `resources_bak`(`ID`, `ResNumber`, `ResourcePath`, `ResThumb`, `ResMid`, `Type`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy`, `Operation`, `OperationUser`) SELECT `ID`, `ResNumber`, `ResourcePath`, `ResThumb`, `ResMid`, `Type`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy` , 'Delete' , :operationuser FROM `resources` WHERE `ResNumber`=:resid");
					$stmt->bindValue(':resid', $_CLEANREQUEST['ResID'], PDO::PARAM_INT);
					$stmt->bindValue(':operationuser', $user->data['username'], PDO::PARAM_STR);
					$stmt->execute();
					
					$logstmt = $Gamedb->prepare("INSERT INTO `modlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
					$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
					$logstmt->bindValue(':link', "", PDO::PARAM_STR);
					$logstmt->bindValue(':id', $Gamedb->lastInsertId(), PDO::PARAM_INT);
					$logstmt->bindValue(':gameid', $id, PDO::PARAM_INT);
					$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
					$logstmt->execute();

					$stmt = $Gamedb->prepare("DELETE FROM resources WHERE `ResNumber`=:resid");
					$stmt->bindValue(':resid', $_CLEANREQUEST['ResID'], PDO::PARAM_INT);
					$stmt->execute();	
					
						
					if($stmt->rowCount()){																					
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
						die('');		
					}else{
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');				
					}
		
				}else{
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');					
				}
			}
		break;
//------------------------------------------------------------------------------------------------------------------------------		
		case "FlagResource":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['FResources']==2  && isset($_CLEANREQUEST['ResourceID'])){
					$stmt = $Gamedb->prepare("UPDATE `resources` SET `Flagged`=1,`FlaggedBy`=:user WHERE `ResNumber`=:ResNumber");
					$stmt->bindValue(':ResNumber', $_CLEANREQUEST['ResourceID'], PDO::PARAM_INT);
					$stmt->bindValue(':user', $user->data['username'], PDO::PARAM_STR);
					$stmt->execute();
					
					if($stmt->rowCount()){
						$stmt = $Gamedb->prepare("SELECT * FROM `resources` WHERE `ResNumber`=:ResNumber");
						$stmt->bindValue(':ResNumber', $_CLEANREQUEST['ResourceID'], PDO::PARAM_INT);
						$stmt->execute();
						
						if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							$logstmt = $Gamedb->prepare("INSERT INTO `userlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
							$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
							$logstmt->bindValue(':link', $row['ResourcePath'], PDO::PARAM_STR);
							$logstmt->bindValue(':id', $_CLEANREQUEST['ResourceID'], PDO::PARAM_INT);
							$logstmt->bindValue(':gameid', $row['ID'], PDO::PARAM_INT);
							$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
							$logstmt->execute();
						}					
					
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
						die('');
					}else{	
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');
					}
				}else{	
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');
				}	
			}
		break;
//------------------------------------------------------------------------------------------------------------------------------		
		case "AcceptResource":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['MResources']==2 && isset($_CLEANREQUEST['ResourceID'])){
					$stmt = $Gamedb->prepare("SELECT * from resources WHERE ResNumber=:ResNumber");
					$stmt->bindValue(':ResNumber', $_CLEANREQUEST['ResourceID'], PDO::PARAM_INT);
					$stmt->execute();
					
					if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						$stmt = $Gamedb->prepare("UPDATE resources SET `Pending`=0, `Flagged`=0, `FlaggedBy`='' WHERE ResNumber=:ResNumber");
						$stmt->bindValue(':ResNumber', $_CLEANREQUEST['ResourceID'], PDO::PARAM_INT);
						$stmt->execute();			
						
						if($stmt->rowCount()){

							$usernameArray = array($temp['CreatedBy']);
							$useridArray = array();
							
							user_get_id_name($useridArray,$usernameArray);
							
							if($useridArray[0]!="NO_USERS" && $useridArray[0]!=""){
								if($row['Type']==4){
									add_points($useridArray[0],$ScreenShotPoints);
								}else{
									add_points($useridArray[0],$FanArtPoints);
								}
							}							
							
							$logstmt = $Gamedb->prepare("INSERT INTO `modlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
							$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
							$logstmt->bindValue(':link', $row['ResourcePath'], PDO::PARAM_STR);
							$logstmt->bindValue(':id', $_CLEANREQUEST['ResourceID'], PDO::PARAM_INT);
							$logstmt->bindValue(':gameid', $row['ID'], PDO::PARAM_INT);
							$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
							$logstmt->execute();
							
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
							die('');
						}else{		
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
							die('');
						}		
					}else{
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');				
					}
				}else{	
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');
				}			
		
			}
		break;		
//------------------------------------------------------------------------------------------------------------------------------		
//------------------------------------------------------------------------------------------------------------------------------		
		case "RestoreGame":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['RGame']==2 && isset($_CLEANREQUEST['AutoID']) && isset($_CLEANREQUEST['ID']) ){
					
					$stmt = $Gamedb->prepare("DELETE FROM `freegames` WHERE ID=:id");
					$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
					$stmt->execute();

					$stmt = $Gamedb->prepare("INSERT INTO `freegames`(`ID`, `Studio`, `Graphics`, `Source`, `Type`, `Name`, `About`, `Trailer`, `Rating`, `RateNum`, `RateTot`, `Release`, `EditedBy`, `CreatedBy`, `PlatformBITS`, `GenreBITS`, `ModeBITS`) SELECT `ID`, `Studio`, `Graphics`, `Source`, `Type`, `Name`, `About`, `Trailer`, `Rating`, `RateNum`, `RateTot`, `Release`, `EditedBy`, `CreatedBy`, `PlatformBITS`, `GenreBITS`, `ModeBITS` FROM `freegames_bak` WHERE AutoID=:autoid");
					$stmt->bindValue(':autoid', $_CLEANREQUEST['AutoID'], PDO::PARAM_INT);
					$stmt->execute();	
					if($stmt->rowCount()){	
						$stmt = $Gamedb->prepare("SELECT * FROM `freegames_bak` WHERE `AutoID`=:autoid");
						$stmt->bindValue(':autoid', $_CLEANREQUEST['AutoID'], PDO::PARAM_INT);
						$stmt->execute();
						
						if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							$logstmt = $Gamedb->prepare("INSERT INTO `modlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
							$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
							$logstmt->bindValue(':link', "", PDO::PARAM_STR);
							$logstmt->bindValue(':id', "", PDO::PARAM_INT);
							$logstmt->bindValue(':gameid', $row['ID'], PDO::PARAM_INT);
							$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
							$logstmt->execute();
						}
						$stmt = $Gamedb->prepare("DELETE FROM `freegames_bak` WHERE `AutoID`=:autoid");
						$stmt->bindValue(':autoid', $_CLEANREQUEST['AutoID'], PDO::PARAM_INT);
						$stmt->execute();
						
						if($stmt->rowCount()){																					
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
							die('');		
						}else{
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
							die('');				
						}
					}else{
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');				
					}					
				}else{
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');					
				}
			}
		break;
//------------------------------------------------------------------------------------------------------------------------------						
		case "DeleteGame":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['DGame']==2 && isset($_CLEANREQUEST['ID'])){
						$stmt = $Gamedb->prepare("SELECT * FROM `freegames` WHERE ID=:id");
						$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$stmt->execute();
						if($temp = $stmt->fetch(PDO::FETCH_ASSOC)){
							
							$usernameArray = array($temp['CreatedBy']);
							$useridArray = array();
							
							user_get_id_name($useridArray,$usernameArray);
							
							if($useridArray[0]!="NO_USERS" && $useridArray[0]!=""){
								substract_points($useridArray[0],$NewGamePoints);
							}
						}
						
						$logstmt = $Gamedb->prepare("INSERT INTO `modlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
						$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
						$logstmt->bindValue(':link', "", PDO::PARAM_STR);
						$logstmt->bindValue(':id', "", PDO::PARAM_INT);
						$logstmt->bindValue(':gameid', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
						$logstmt->execute();
						
						//Backup and Delete Game Entry
						$stmt = $Gamedb->prepare("INSERT INTO `freegames_bak`(`ID`, `Studio`, `Graphics`, `Source`, `Type`, `Name`, `About`, `Trailer`, `Rating`, `RateNum`, `RateTot`, `Release`, `EditedBy`, `CreatedBy`, `PlatformBITS`, `GenreBITS`, `ModeBITS`, `Operation`, `OperationUser`) SELECT `ID`, `Studio`, `Graphics`, `Source`, `Type`, `Name`, `About`, `Trailer`, `Rating`, `RateNum`, `RateTot`, `Release`, `EditedBy`, `CreatedBy`, `PlatformBITS`, `GenreBITS`, `ModeBITS`, 'Delete', :operationuser FROM `freegames` WHERE ID=:id");
						$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$stmt->bindValue(':operationuser', $user->data['username'], PDO::PARAM_STR);
						$stmt->execute();
						$stmt = $Gamedb->prepare("DELETE FROM `freegames` WHERE ID=:id");
						$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$stmt->execute();
						
						//Backup and Delete Download Links
						$stmt = $Gamedb->prepare("INSERT INTO `downloadsbits_bak`(`DownloadID`, `ID`, `OS`, `Version`, `Platform`, `FileSize`, `Download`, `Direct`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy`, `Operation`, `OperationUser`) SELECT `DownloadID`, `ID`, `OS`, `Version`, `Platform`, `FileSize`, `Download`, `Direct`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy`, 'DeleteGame', :operationuser FROM `downloadsbits` WHERE ID=:id");
						$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$stmt->bindValue(':operationuser', $user->data['username'], PDO::PARAM_STR);
						$stmt->execute();	
						$stmt = $Gamedb->prepare("DELETE FROM `downloadsbits` WHERE ID=:id");
						$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$stmt->execute();	
						
						//Backup and Delete Resources
						$stmt = $Gamedb->prepare("INSERT INTO `resources_bak`(`ID`, `ResNumber`, `ResourcePath`, `ResThumb`, `ResMid`, `Type`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy`, `Operation`, `OperationUser`) SELECT `ID`, `ResNumber`, `ResourcePath`, `ResThumb`, `ResMid`, `Type`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy` , 'DeleteGame' , :operationuser FROM `resources` WHERE ID=:id");
						$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$stmt->bindValue(':operationuser', $user->data['username'], PDO::PARAM_STR);
						$stmt->execute();
						$stmt = $Gamedb->prepare("DELETE FROM `resources` WHERE ID=:id");
						$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$stmt->execute();
						
						
						//Backup and Delete Website Links
						$stmt = $Gamedb->prepare("INSERT INTO `websites_bak`(`WebsiteID`, `ID`, `Website`, `Notes`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy`, `Operation`, `OperationUser`) SELECT `WebsiteID`, `ID`, `Website`, `Notes`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy`, 'DeleteGame', :operationuser FROM `websites` WHERE ID=:id");
						$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$stmt->bindValue(':operationuser', $user->data['username'], PDO::PARAM_STR);
						$stmt->execute();
						$stmt = $Gamedb->prepare("DELETE FROM `websites` WHERE ID=:id");
						$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$stmt->execute();	
						
						//Backup and Delete Reviews
						$stmt = $Gamedb->prepare("INSERT INTO `reviews_bak`(`UniqueID`, `Review`, `ID`, `Author`, `Pros`, `Cons`, `Rating`, `Pending`, `Flagged`, `FlaggedBy`, `Operation`, `OperationUser`) SELECT `UniqueID`, `Review`, `ID`, `Author`, `Pros`, `Cons`, `Rating`, `Pending`, `Flagged`, `FlaggedBy`, 'DeleteGame', :operationuser FROM `reviews` WHERE ID=:id");
						$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$stmt->bindValue(':operationuser', $user->data['username'], PDO::PARAM_STR);
						$stmt->execute();	
						$stmt = $Gamedb->prepare("DELETE FROM `reviews` WHERE ID=:id");
						$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$stmt->execute();	

						//Delete Ratings
						$stmt = $Gamedb->prepare("DELETE FROM `rating` WHERE ID=:id");
						$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$stmt->execute();	
						
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
						die('');
					
				}else{
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');
				}
			}
		
		break;
//------------------------------------------------------------------------------------------------------------------------------
		case "AddGame":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['AGame']==2 && ISSET($_CLEANREQUEST['About']) && ISSET($_CLEANREQUEST['Trail']) && ISSET($_CLEANREQUEST['Source'])
				&& ISSET($_CLEANREQUEST['Studio']) && ISSET($_CLEANREQUEST['Graphics']) && ISSET($_CLEANREQUEST['Type']) && ISSET($_CLEANREQUEST['Release']) && isset($_CLEANREQUEST['Name']) && isset($_CLEANREQUEST['genres']) && isset($_CLEANREQUEST['modes']) && isset($_CLEANREQUEST['platforms']) && ISSET($_CLEANREQUEST['Age'])){
				
					$Genre = $_CLEANREQUEST['genres'];
					$Mode = $_CLEANREQUEST['modes'];
					$Platform = $_CLEANREQUEST['platforms'];
					
					$stmt = $Gamedb->prepare("INSERT INTO freegames (`ID`, `Studio`, `Graphics`, `Source`, `Type`, `Name`, `About`, `Age`, `Trailer`, `Rating`, `RateNum`, `RateTot`, `Release`, `EditedBy`, `CreatedBy`, `PlatformBITS`, `GenreBITS`, `ModeBITS`) VALUES (null,:studio,:graphics,:source,:type,:name,:about,:age,:trailer,0,0,0,:release,'',:createdby,:platform,:genre,:mode)");
						
					$stmt->bindValue(':name', $_CLEANREQUEST['Name'], PDO::PARAM_STR);
					$stmt->bindValue(':createdby', $user->data['username'], PDO::PARAM_STR);
					$stmt->bindValue(':about', $_CLEANREQUEST['About'], PDO::PARAM_STR);
					$stmt->bindValue(':trailer', $_CLEANREQUEST['Trail'], PDO::PARAM_STR);
					$stmt->bindValue(':source', $_CLEANREQUEST['Source'], PDO::PARAM_INT);
					$stmt->bindValue(':studio', $_CLEANREQUEST['Studio'], PDO::PARAM_INT);
					$stmt->bindValue(':graphics', $_CLEANREQUEST['Graphics'], PDO::PARAM_INT);
					$stmt->bindValue(':type', $_CLEANREQUEST['Type'], PDO::PARAM_INT);
					$stmt->bindValue(':release', $_CLEANREQUEST['Release'], PDO::PARAM_INT);
					$stmt->bindValue(':age', $_CLEANREQUEST['Age'], PDO::PARAM_INT);
					$stmt->bindValue(':platform', $Platform, PDO::PARAM_INT);
					$stmt->bindValue(':genre', $Genre, PDO::PARAM_INT);
					$stmt->bindValue(':mode', $Mode, PDO::PARAM_INT);
					
					$stmt->execute();
					
					$id = $Gamedb->lastInsertId();

					$logstmt = $Gamedb->prepare("INSERT INTO `userlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
					$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
					$logstmt->bindValue(':link', "Games/".$Gamedb->lastInsertId()."//", PDO::PARAM_STR);
					$logstmt->bindValue(':id', "", PDO::PARAM_INT);
					$logstmt->bindValue(':gameid', $Gamedb->lastInsertId(), PDO::PARAM_INT);
					$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
					$logstmt->execute();
					
					
					
					if($stmt->rowCount()){
					
						
						
						$stmt = $Gamedb->prepare("SELECT * FROM `freegames` WHERE `ID`=:id");
						$stmt->bindValue(':id', $id, PDO::PARAM_INT);
						$stmt->execute();	
						
						if($temp = $stmt->fetch(PDO::FETCH_ASSOC)){
							add_points($user->data['user_id'],$NewGamePoints);
							header( "Location: ".$DOMAIN."/".str_replace("[Name]",$temp['Name'],str_replace("[ID]",$temp['ID'],$_CLEANREQUEST['Return'])));
							die('');						
						}else{
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
							die('');						
						}
					}else{
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');
					}
									
				}else{
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');		
				}
			}
		break;
//------------------------------------------------------------------------------------------------------------------------------
		case "UpdateGame":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['EGame']==2 && isset($_CLEANREQUEST['ID']) && ISSET($_CLEANREQUEST['About']) && ISSET($_CLEANREQUEST['Trail']) && ISSET($_CLEANREQUEST['Source']) && ISSET($_CLEANREQUEST['Studio']) && ISSET($_CLEANREQUEST['Graphics']) && ISSET($_CLEANREQUEST['Type']) && ISSET($_CLEANREQUEST['Release']) && (isset($_CLEANREQUEST['Name']) || $FDBPrivilages['NGame']!=2) && ISSET($_CLEANREQUEST['platforms']) && ISSET($_CLEANREQUEST['modes']) && ISSET($_CLEANREQUEST['genres']) && ISSET($_CLEANREQUEST['Age'])){
				
					$Genre = $_CLEANREQUEST['genres'];
					$Mode = $_CLEANREQUEST['modes'];
					$Platform = $_CLEANREQUEST['platforms'];
					
					$stmt = $Gamedb->prepare("INSERT INTO `freegames_bak`(`ID`, `Studio`, `Graphics`, `Source`, `Type`, `Name`, `About`, `Age`, `Trailer`, `Rating`, `RateNum`, `RateTot`, `Release`, `EditedBy`, `CreatedBy`, `PlatformBITS`, `GenreBITS`, `ModeBITS`, `Operation`, `OperationUser`) SELECT `ID`, `Studio`, `Graphics`, `Source`, `Type`, `Name`, `About`, `Age`, `Trailer`, `Rating`, `RateNum`, `RateTot`, `Release`, `EditedBy`, `CreatedBy`, `PlatformBITS`, `GenreBITS`, `ModeBITS`, 'Update', :operationuser FROM `freegames` WHERE ID=:id");
					$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
					$stmt->bindValue(':operationuser', $user->data['username'], PDO::PARAM_STR);
					$stmt->execute();	
					
				
					if(!($stmt->rowCount())){
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');						
					}
					
					if($FDBPrivilages['NGame']==2){
					
						$stmt = $Gamedb->prepare("UPDATE freegames SET Name=:name , EditedBy=:editedby , About=:about, Trailer=:trailer, Source=:source, Studio=:studio, Graphics=:graphics, Type=:type, `Release`=:release, PlatformBITS=:platform, GenreBITS=:genre, ModeBITS=:mode, Age=:age WHERE ID=:id");
						
						$stmt->bindValue(':name', $_CLEANREQUEST['Name'], PDO::PARAM_STR);
						$stmt->bindValue(':editedby', $user->data['username'], PDO::PARAM_STR);
						$stmt->bindValue(':about', $_CLEANREQUEST['About'], PDO::PARAM_STR);
						$stmt->bindValue(':trailer', $_CLEANREQUEST['Trail'], PDO::PARAM_STR);
						$stmt->bindValue(':source', $_CLEANREQUEST['Source'], PDO::PARAM_INT);
						$stmt->bindValue(':studio', $_CLEANREQUEST['Studio'], PDO::PARAM_INT);
						$stmt->bindValue(':graphics', $_CLEANREQUEST['Graphics'], PDO::PARAM_INT);
						$stmt->bindValue(':type', $_CLEANREQUEST['Type'], PDO::PARAM_INT);
						$stmt->bindValue(':release', $_CLEANREQUEST['Release'], PDO::PARAM_INT);
						$stmt->bindValue(':platform', $Platform, PDO::PARAM_INT);
						$stmt->bindValue(':genre', $Genre, PDO::PARAM_INT);
						$stmt->bindValue(':mode', $Mode, PDO::PARAM_INT);
						$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$stmt->bindValue(':age', $_CLEANREQUEST['Age'], PDO::PARAM_INT);
						$stmt->execute();
					
					}else{
					
						$stmt = $Gamedb->prepare("UPDATE freegames SET EditedBy=:editedby , About=:about, Trailer=:trailer, Source=:source, Studio=:studio, Graphics=:graphics, Type=:type, `Release`=:release, PlatformBITS=:platform, GenreBITS=:genre, ModeBITS=:mode, Age=:age WHERE ID=:id");
						
						$stmt->bindValue(':editedby', $user->data['username'], PDO::PARAM_STR);
						$stmt->bindValue(':about', $_CLEANREQUEST['About'], PDO::PARAM_STR);
						$stmt->bindValue(':trailer', $_CLEANREQUEST['Trail'], PDO::PARAM_STR);
						$stmt->bindValue(':source', $_CLEANREQUEST['Source'], PDO::PARAM_INT);
						$stmt->bindValue(':studio', $_CLEANREQUEST['Studio'], PDO::PARAM_INT);
						$stmt->bindValue(':graphics', $_CLEANREQUEST['Graphics'], PDO::PARAM_INT);
						$stmt->bindValue(':type', $_CLEANREQUEST['Type'], PDO::PARAM_INT);
						$stmt->bindValue(':release', $_CLEANREQUEST['Release'], PDO::PARAM_INT);
						$stmt->bindValue(':platform', $Platform, PDO::PARAM_INT);
						$stmt->bindValue(':genre', $Genre, PDO::PARAM_INT);
						$stmt->bindValue(':mode', $Mode, PDO::PARAM_INT);
						$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$stmt->bindValue(':age', $_CLEANREQUEST['Age'], PDO::PARAM_INT);
						$stmt->execute();
					
					}
					
					$logstmt = $Gamedb->prepare("INSERT INTO `userlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
					$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
					$logstmt->bindValue(':link', "", PDO::PARAM_STR);
					$logstmt->bindValue(':id', "", PDO::PARAM_INT);
					$logstmt->bindValue(':gameid', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
					$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
					$logstmt->execute();
					
					if($stmt->rowCount()){					
						add_points($user->data['user_id'],$EditGamePoints);
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
						die('');						
					}else{
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');					
					}				
				}else{
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');		
				}
			}
		break;
//------------------------------------------------------------------------------------------------------------------------------		
		case "MergeGame":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['MGame']==2 && isset($_CLEANREQUEST['ID']) && isset($_CLEANREQUEST['MergeID'])){
					$stmt = $Gamedb->prepare("SELECT * FROM freegames WHERE ID=:id");
					$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
					$stmt->execute();
					
					$stmt2 = $Gamedb->prepare("SELECT * FROM freegames WHERE ID=:id");
					$stmt2->bindValue(':id', $_CLEANREQUEST["MergeID"], PDO::PARAM_INT);
					$stmt2->execute();
					
					if($Namerow = $stmt->fetch(PDO::FETCH_ASSOC) && $Namerow2 = $stmt2->fetch(PDO::FETCH_ASSOC) && $_CLEANREQUEST["MergeID"]!=$_CLEANREQUEST['ID']){
					
						$logstmt = $Gamedb->prepare("INSERT INTO `modlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
						$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
						$logstmt->bindValue(':link', "", PDO::PARAM_STR);
						$logstmt->bindValue(':id', $_CLEANREQUEST['MergeID'], PDO::PARAM_INT);
						$logstmt->bindValue(':gameid', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
						$logstmt->execute();
					
						$rating = $Namerow['Rating'] + $Namerow2['Rating'];
						$ratenum = $Namerow['RateNum'] + $Namerow2['RateNum'];
						$ratetot = $Namerow['RateTot'] + $Namerow2['RateTot'];
						
						$stmt = $Gamedb->prepare("UPDATE `resources` SET `ID`=:id WHERE ID=:id2");
						$stmt->bindValue(':id2', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
						$stmt->bindValue(':id', $_CLEANREQUEST["MergeID"], PDO::PARAM_INT);
						$stmt->execute();
						
						$stmt = $Gamedb->prepare("UPDATE `reviews` SET `ID`=:id WHERE ID=:id2");
						$stmt->bindValue(':id2', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
						$stmt->bindValue(':id', $_CLEANREQUEST["MergeID"], PDO::PARAM_INT);
						$stmt->execute();
					
						$stmt = $Gamedb->prepare("UPDATE `websites` SET `ID`=:id WHERE ID=:id2");
						$stmt->bindValue(':id2', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
						$stmt->bindValue(':id', $_CLEANREQUEST["MergeID"], PDO::PARAM_INT);
						$stmt->execute();
						
						$stmt = $Gamedb->prepare("UPDATE `rating` SET `ID`=:id WHERE ID=:id2");
						$stmt->bindValue(':id2', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
						$stmt->bindValue(':id', $_CLEANREQUEST["MergeID"], PDO::PARAM_INT);
						$stmt->execute();
						
						$stmt = $Gamedb->prepare("UPDATE `downloadsbits` SET `ID`=:id WHERE ID=:id2");
						$stmt->bindValue(':id2', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
						$stmt->bindValue(':id', $_CLEANREQUEST["MergeID"], PDO::PARAM_INT);
						$stmt->execute();
						
						$stmt = $Gamedb->prepare("UPDATE `freegames` SET `Rating`=:rating, `RateNum`=:ratenum, `RateTot`=:ratetot WHERE ID=:id");
						$stmt->bindValue(':id', $_CLEANREQUEST["MergeID"], PDO::PARAM_INT);
						$stmt->bindValue(':rating', $rating, PDO::PARAM_INT);
						$stmt->bindValue(':ratenum', $ratenum, PDO::PARAM_INT);
						$stmt->bindValue(':ratetot', $ratetot, PDO::PARAM_INT);
						$stmt->execute();
					
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
						die('');	
					
					}else{
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');		
					}	
				}else{
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');		
				}
			}
		break;
//------------------------------------------------------------------------------------------------------------------------------		
		case "RateGame":
			if($_CLEANREQUEST['Protocol']=="AJAX"){
			
				$aResponse['error'] = false;
				$aResponse['message'] = '';
				$success = false;
				
				if($FDBPrivilages['ARating']==2 && isset($_CLEANREQUEST['Rate']) && isset($_CLEANREQUEST['ID'])){						
					$stmt = $Gamedb->prepare("SELECT * FROM freegames WHERE ID=:id");
					$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
					$stmt->execute();
					
					if($Namerow = $stmt->fetch(PDO::FETCH_ASSOC)){
						$stmt = $Gamedb->prepare("SELECT * FROM `rating` WHERE ID=:id AND User=:user");
						$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
						$stmt->bindValue(':user', $user->data['username'], PDO::PARAM_STR);
						$stmt->execute();		
						
						if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
								
							//Change Current Rating
							$OldRate = $row['Rating'];
							$NewRate = $_CLEANREQUEST["Rate"];	
							
							$RateTot = intval($Namerow['RateTot']) - intval($OldRate) + intval($NewRate);
							$Rate = intval($RateTot) / intval($Namerow['RateNum']);
							$Rate = round($Rate,2);
						
							$stmt = $Gamedb->prepare("UPDATE `freegames` SET `Rating`=:rate,`RateTot`=:ratetot WHERE ID=:id");
							$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
							$stmt->bindValue(':ratetot', $RateTot, PDO::PARAM_STR);
							$stmt->bindValue(':rate', $Rate, PDO::PARAM_STR);
							$stmt->execute();
							
							$stmt = $Gamedb->prepare("UPDATE `rating` SET `Rating`=:rate WHERE User=:user AND ID=:id");
							$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
							$stmt->bindValue(':user', $user->data['username'], PDO::PARAM_STR);
							$stmt->bindValue(':rate', $_CLEANREQUEST["Rate"], PDO::PARAM_STR);
							$stmt->execute();
							
							$success = true;
								
								
						}else{
							//Add new Rating
							$stmt = $Gamedb->prepare("INSERT INTO `rating`(`UniqueID`, `ID`, `Rating`, `User`) VALUES (null ,:id,:rate,:user)");
							$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
							$stmt->bindValue(':user', $user->data['username'], PDO::PARAM_STR);
							$stmt->bindValue(':rate', $_CLEANREQUEST["Rate"], PDO::PARAM_STR);
							$stmt->execute();
													
							$RateTot = intval($Namerow['RateTot']) + intval($_CLEANREQUEST["Rate"]);
							$RateNum = intval($Namerow['RateNum']) + 1;
							$Rate = intval($RateTot) / intval($RateNum);
							$Rate = round($Rate,2);
							
							$stmt = $Gamedb->prepare("UPDATE `freegames` SET `Rating`=:rate,`RateNum`=:rateNum,`RateTot`=:rateTot WHERE ID=:id");
							$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
							$stmt->bindValue(':rate', $Rate, PDO::PARAM_STR);
							$stmt->bindValue(':rateNum', $RateNum, PDO::PARAM_STR);
							$stmt->bindValue(':rateTot', $RateTot, PDO::PARAM_STR);
							$stmt->execute();				
							
							add_points($user->data['user_id'],$RatingPoints);
							$success = true;
							
						}						
					}				
					
					// json datas send to the js file
					if($success){
						$logstmt = $Gamedb->prepare("INSERT INTO `userlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
						$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
						$logstmt->bindValue(':link', "", PDO::PARAM_STR);
						$logstmt->bindValue(':id', "", PDO::PARAM_INT);
						$logstmt->bindValue(':gameid', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
						$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
						$logstmt->execute();					
					
						$aResponse['message'] = 'Your rating has been successfuly recorded. Thanks for your rate :)';
						
						echo json_encode($aResponse);
					}else{
						$aResponse['error'] = true;
						$aResponse['message'] = 'An error occured during the request. Please retry';
						
						echo json_encode($aResponse);
					}
				
				}elseif(isset($_CLEANREQUEST['Rate']) && isset($_CLEANREQUEST['ID'])){
				
					$stmt = $Gamedb->prepare("SELECT * FROM freegames WHERE ID=:id");
					$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
					$stmt->execute();
					
					if($Namerow = $stmt->fetch(PDO::FETCH_ASSOC)){
						$stmt = $Gamedb->prepare("SELECT * FROM `rating` WHERE ID=:id AND IPUser=:user");
						$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
						$stmt->bindValue(':user', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
						$stmt->execute();		
						
						if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
								
							//Change Current Rating
							$OldRate = $row['Rating'];
							$NewRate = $_CLEANREQUEST["Rate"];	
							
							$RateTot = intval($Namerow['RateTot']) - intval($OldRate) + intval($NewRate);
							$Rate = intval($RateTot) / intval($Namerow['RateNum']);
							$Rate = round($Rate,2);
						
							$stmt = $Gamedb->prepare("UPDATE `freegames` SET `Rating`=:rate,`RateTot`=:ratetot WHERE ID=:id");
							$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
							$stmt->bindValue(':ratetot', $RateTot, PDO::PARAM_STR);
							$stmt->bindValue(':rate', $Rate, PDO::PARAM_STR);
							$stmt->execute();
							
							$stmt = $Gamedb->prepare("UPDATE `rating` SET `Rating`=:rate WHERE IPUser=:user AND ID=:id");
							$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
							$stmt->bindValue(':user', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
							$stmt->bindValue(':rate', $_CLEANREQUEST["Rate"], PDO::PARAM_STR);
							$stmt->execute();
							
							$success = true;
								
								
						}else{
							//Add new Rating
							$stmt = $Gamedb->prepare("INSERT INTO `rating`(`UniqueID`, `ID`, `Rating`, `User` , `IPUser`) VALUES (null ,:id,:rate,'',:user)");
							$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
							$stmt->bindValue(':user', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
							$stmt->bindValue(':rate', $_CLEANREQUEST["Rate"], PDO::PARAM_STR);
							$stmt->execute();
													
							$RateTot = intval($Namerow['RateTot']) + intval($_CLEANREQUEST["Rate"]);
							$RateNum = intval($Namerow['RateNum']) + 1;
							$Rate = intval($RateTot) / intval($RateNum);
							$Rate = round($Rate,2);
							
							$stmt = $Gamedb->prepare("UPDATE `freegames` SET `Rating`=:rate,`RateNum`=:rateNum,`RateTot`=:rateTot WHERE ID=:id");
							$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
							$stmt->bindValue(':rate', $Rate, PDO::PARAM_STR);
							$stmt->bindValue(':rateNum', $RateNum, PDO::PARAM_STR);
							$stmt->bindValue(':rateTot', $RateTot, PDO::PARAM_STR);
							$stmt->execute();				
							
							$success = true;
							
						}						
					}				
					
					// json datas send to the js file
					if($success){
						$logstmt = $Gamedb->prepare("INSERT INTO `userlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
						$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
						$logstmt->bindValue(':link', "", PDO::PARAM_STR);
						$logstmt->bindValue(':id', "", PDO::PARAM_INT);
						$logstmt->bindValue(':gameid', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
						$logstmt->bindValue(':username', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
						$logstmt->execute();					
					
						$aResponse['message'] = 'Your rating has been successfully recorded. Thanks for your rate :)';
						
						echo json_encode($aResponse);
					}else{
						$aResponse['error'] = true;
						$aResponse['message'] = 'An error occurred during the request. Please retry';
						
						echo json_encode($aResponse);
					}
					
					
				}else{
					$aResponse['error'] = true;
					$aResponse['message'] = '$_POST[\'action\'] not found';

					echo json_encode($aResponse);
				}			
			}
			
			if($_CLEANREQUEST['Protocol']=="POST"){
			
				$success = false;
				
				if($FDBPrivilages['ARating']==2 && isset($_CLEANREQUEST['Rate']) && isset($_CLEANREQUEST['ID'])){						
					$stmt = $Gamedb->prepare("SELECT * FROM freegames WHERE ID=:id");
					$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
					$stmt->execute();
					
					if($Namerow = $stmt->fetch(PDO::FETCH_ASSOC)){
						$stmt = $Gamedb->prepare("SELECT * FROM `rating` WHERE ID=:id AND User=:user");
						$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
						$stmt->bindValue(':user', $user->data['username'], PDO::PARAM_STR);
						$stmt->execute();		
						
						if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
								
							//Change Current Rating
							$OldRate = $row['Rating'];
							$NewRate = $_CLEANREQUEST["Rate"];	
							
							$RateTot = intval($Namerow['RateTot']) - intval($OldRate) + intval($NewRate);
							$Rate = intval($RateTot) / intval($Namerow['RateNum']);
							$Rate = round($Rate,2);
						
							$stmt = $Gamedb->prepare("UPDATE `freegames` SET `Rating`=:rate,`RateTot`=:ratetot WHERE ID=:id");
							$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
							$stmt->bindValue(':ratetot', $RateTot, PDO::PARAM_STR);
							$stmt->bindValue(':rate', $Rate, PDO::PARAM_STR);
							$stmt->execute();
							
							$stmt = $Gamedb->prepare("UPDATE `rating` SET `Rating`=:rate WHERE User=:user AND ID=:id");
							$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
							$stmt->bindValue(':user', $user->data['username'], PDO::PARAM_STR);
							$stmt->bindValue(':rate', $_CLEANREQUEST["Rate"], PDO::PARAM_STR);
							$stmt->execute();
							
							$success = true;
								
						}else{
							//Add new Rating
							$stmt = $Gamedb->prepare("INSERT INTO `rating`(`UniqueID`, `ID`, `Rating`, `User`) VALUES (null ,:id,:rate,:user)");
							$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
							$stmt->bindValue(':user', $user->data['username'], PDO::PARAM_STR);
							$stmt->bindValue(':rate', $_CLEANREQUEST["Rate"], PDO::PARAM_STR);
							$stmt->execute();
													
							$RateTot = intval($Namerow['RateTot']) + intval($_CLEANREQUEST["Rate"]);
							$RateNum = intval($Namerow['RateNum']) + 1;
							$Rate = intval($RateTot) / intval($RateNum);
							$Rate = round($Rate,2);
							
							$stmt = $Gamedb->prepare("UPDATE `freegames` SET `Rating`=:rate,`RateNum`=:rateNum,`RateTot`=:rateTot WHERE ID=:id");
							$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
							$stmt->bindValue(':rate', $Rate, PDO::PARAM_STR);
							$stmt->bindValue(':rateNum', $RateNum, PDO::PARAM_STR);
							$stmt->bindValue(':rateTot', $RateTot, PDO::PARAM_STR);
							$stmt->execute();				
							
							add_points($user->data['user_id'],$RatingPoints);
							$success = true;
							
						}						
					}				
					
					// json datas send to the js file
					if($success){
						$logstmt = $Gamedb->prepare("INSERT INTO `userlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
						$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
						$logstmt->bindValue(':link', "Games/".$_CLEANREQUEST["ID"]."//", PDO::PARAM_STR);
						$logstmt->bindValue(':id', "", PDO::PARAM_INT);
						$logstmt->bindValue(':gameid', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
						$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
						$logstmt->execute();	
					
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
						die('');
					}else{
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');
					}
				
				}elseif(isset($_CLEANREQUEST['Rate']) && isset($_CLEANREQUEST['ID'])){

					$stmt = $Gamedb->prepare("SELECT * FROM freegames WHERE ID=:id");
					$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
					$stmt->execute();
					
					if($Namerow = $stmt->fetch(PDO::FETCH_ASSOC)){
						$stmt = $Gamedb->prepare("SELECT * FROM `rating` WHERE ID=:id AND IPUser=:user");
						$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
						$stmt->bindValue(':user', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
						$stmt->execute();		
						
						if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
								
							//Change Current Rating
							$OldRate = $row['Rating'];
							$NewRate = $_CLEANREQUEST["Rate"];	
							
							$RateTot = intval($Namerow['RateTot']) - intval($OldRate) + intval($NewRate);
							$Rate = intval($RateTot) / intval($Namerow['RateNum']);
							$Rate = round($Rate,2);
						
							$stmt = $Gamedb->prepare("UPDATE `freegames` SET `Rating`=:rate,`RateTot`=:ratetot WHERE ID=:id");
							$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
							$stmt->bindValue(':ratetot', $RateTot, PDO::PARAM_STR);
							$stmt->bindValue(':rate', $Rate, PDO::PARAM_STR);
							$stmt->execute();
							
							$stmt = $Gamedb->prepare("UPDATE `rating` SET `Rating`=:rate WHERE IPUser=:user AND ID=:id");
							$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
							$stmt->bindValue(':user', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
							$stmt->bindValue(':rate', $_CLEANREQUEST["Rate"], PDO::PARAM_STR);
							$stmt->execute();
							
							$success = true;
								
						}else{
							//Add new Rating
							$stmt = $Gamedb->prepare("INSERT INTO `rating`(`UniqueID`, `ID`, `Rating`, `User` ,`IPUser`) VALUES (null ,:id,:rate,'',:user)");
							$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
							$stmt->bindValue(':user', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
							$stmt->bindValue(':rate', $_CLEANREQUEST["Rate"], PDO::PARAM_STR);
							$stmt->execute();
													
							$RateTot = intval($Namerow['RateTot']) + intval($_CLEANREQUEST["Rate"]);
							$RateNum = intval($Namerow['RateNum']) + 1;
							$Rate = intval($RateTot) / intval($RateNum);
							$Rate = round($Rate,2);
							
							$stmt = $Gamedb->prepare("UPDATE `freegames` SET `Rating`=:rate,`RateNum`=:rateNum,`RateTot`=:rateTot WHERE ID=:id");
							$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
							$stmt->bindValue(':rate', $Rate, PDO::PARAM_STR);
							$stmt->bindValue(':rateNum', $RateNum, PDO::PARAM_STR);
							$stmt->bindValue(':rateTot', $RateTot, PDO::PARAM_STR);
							$stmt->execute();				
							
							$success = true;
							
						}						
					}				
					
					// json datas send to the js file
					if($success){
						$logstmt = $Gamedb->prepare("INSERT INTO `userlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
						$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
						$logstmt->bindValue(':link', "Games/".$_CLEANREQUEST["ID"]."//", PDO::PARAM_STR);
						$logstmt->bindValue(':id', "", PDO::PARAM_INT);
						$logstmt->bindValue(':gameid', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
						$logstmt->bindValue(':username', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
						$logstmt->execute();	
					
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
						die('');
					}else{
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');
					}
				
				}else{
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');
				}			
			}			
		break;
//------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------		
		case "RestoreWebLink":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['RWLinks']==2 && isset($_CLEANREQUEST['AutoID'])){
					$stmt = $Gamedb->prepare("INSERT INTO `websites`(`WebsiteID`, `ID`, `Website`, `Notes`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy` ) SELECT `WebsiteID`, `ID`, `Website`, `Notes`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy` FROM `websites_bak` WHERE AutoID=:autoid");
					$stmt->bindValue(':autoid', $_CLEANREQUEST['AutoID'], PDO::PARAM_INT);
					$stmt->execute();	
					if($stmt->rowCount()){	
						$stmt = $Gamedb->prepare("SELECT * FROM `websites_bak` WHERE `AutoID`=:autoid");
						$stmt->bindValue(':autoid', $_CLEANREQUEST['AutoID'], PDO::PARAM_INT);
						$stmt->execute();
						
						if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							$logstmt = $Gamedb->prepare("INSERT INTO `modlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
							$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
							$logstmt->bindValue(':link', $row['Website'], PDO::PARAM_STR);
							$logstmt->bindValue(':id', $row['WebsiteID'], PDO::PARAM_INT);
							$logstmt->bindValue(':gameid', $row['ID'], PDO::PARAM_INT);
							$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
							$logstmt->execute();
						}					
						$stmt = $Gamedb->prepare("DELETE FROM `websites_bak` WHERE `AutoID`=:autoid");
						$stmt->bindValue(':autoid', $_CLEANREQUEST['AutoID'], PDO::PARAM_INT);
						$stmt->execute();
						
						if($stmt->rowCount()){																					
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
							die('');		
						}else{
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
							die('');				
						}
					}else{
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');				
					}					
				}else{
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');					
				}
			}
		break;
//------------------------------------------------------------------------------------------------------------------------------		
		case "DeleteWebLink":
			if($FDBPrivilages['DWLinks']==2 && isset($_CLEANREQUEST['WebsiteID'])){
				if($_CLEANREQUEST['Protocol']=="AJAX"){
					$aResponse['error'] = 1;
				}
			
				$stmt = $Gamedb->prepare("INSERT INTO `websites_bak`(`WebsiteID`, `ID`, `Website`, `Notes`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy`, `Operation`, `OperationUser`) SELECT `WebsiteID`, `ID`, `Website`, `Notes`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy`, 'Delete', :operationuser FROM `websites` WHERE WebsiteID=:Websiteid");
				$stmt->bindValue(':Websiteid', $_CLEANREQUEST['WebsiteID'], PDO::PARAM_INT);
				$stmt->bindValue(':operationuser', $user->data['username'], PDO::PARAM_STR);
				$stmt->execute();	
				$id = $Gamedb->lastInsertId();
				
				$stmt = $Gamedb->prepare("SELECT * FROM `websites_bak` WHERE WebsiteID=:Websiteid");
				$stmt->bindValue(':Websiteid', $_CLEANREQUEST['WebsiteID'], PDO::PARAM_INT);
				$stmt->execute();	
			
				if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$logstmt = $Gamedb->prepare("INSERT INTO `modlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
					$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
					$logstmt->bindValue(':link', $row['Website'], PDO::PARAM_STR);
					$logstmt->bindValue(':id', $id , PDO::PARAM_INT);
					$logstmt->bindValue(':gameid', $row['ID'], PDO::PARAM_INT);
					$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
					$logstmt->execute();
				}
				
				$stmt = $Gamedb->prepare("DELETE FROM `websites` WHERE WebsiteID=:Websiteid");
				$stmt->bindValue(':Websiteid', $_CLEANREQUEST['WebsiteID'], PDO::PARAM_INT);
				$stmt->execute();
				
				
				
				if($stmt->rowCount()){
					if($_CLEANREQUEST['Protocol']=="POST"){
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
						die('');
					}else if($_CLEANREQUEST['Protocol']=="AJAX"){
						$aResponse['error'] = 0;
					}
				}else{	
					if($_CLEANREQUEST['Protocol']=="POST"){
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');
					}else if($_CLEANREQUEST['Protocol']=="AJAX"){
						$aResponse['error'] = 1;
						$aResponse['errorMessage'] = "1";						
					}
				}		
			}else{	
				if($_CLEANREQUEST['Protocol']=="POST"){
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');
				}else if($_CLEANREQUEST['Protocol']=="AJAX"){
					$aResponse['error'] = 1;
					$aResponse['errorMessage'] = "2";
				}
			}			
			if($_CLEANREQUEST['Protocol']=="AJAX"){
				echo json_encode($aResponse);
			}
			
			break;
			
//------------------------------------------------------------------------------------------------------------------------------		
		case "FlagWebLink":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['FWLinks']==2  && isset($_CLEANREQUEST['WebsiteID'])){
					$stmt = $Gamedb->prepare("UPDATE `websites` SET `Flagged`=1,`FlaggedBy`=:user WHERE `WebsiteID`=:websiteid");
					$stmt->bindValue(':websiteid', $_CLEANREQUEST['WebsiteID'], PDO::PARAM_INT);
					$stmt->bindValue(':user', $user->data['username'], PDO::PARAM_STR);
					$stmt->execute();
					
					if($stmt->rowCount()){
						$stmt = $Gamedb->prepare("SELECT * FROM `websites` WHERE `WebsiteID`=:websiteid");
						$stmt->bindValue(':websiteid', $_CLEANREQUEST['WebsiteID'], PDO::PARAM_INT);
						$stmt->execute();
						
						if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							$logstmt = $Gamedb->prepare("INSERT INTO `userlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
							$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
							$logstmt->bindValue(':link', $row['Website'], PDO::PARAM_STR);
							$logstmt->bindValue(':id', $_CLEANREQUEST['WebsiteID'], PDO::PARAM_INT);
							$logstmt->bindValue(':gameid', $row['ID'], PDO::PARAM_INT);
							$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
							$logstmt->execute();
						}	
					
					
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
						die('');
					}else{	
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');
					}
				}else{	
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');
				}	
			}
		break;
//------------------------------------------------------------------------------------------------------------------------------		
		case "AcceptWebLink":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['MWLinks']==2 && isset($_CLEANREQUEST['WebsiteID'])){
					$stmt = $Gamedb->prepare("SELECT * from websites WHERE WebsiteID=:websiteid");
					$stmt->bindValue(':websiteid', $_CLEANREQUEST['WebsiteID'], PDO::PARAM_INT);
					$stmt->execute();
					
					if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						$stmt = $Gamedb->prepare("UPDATE websites SET `Pending`=0, `Flagged`=0, `FlaggedBy`='' WHERE WebsiteID=:websiteid");
						$stmt->bindValue(':websiteid', $_CLEANREQUEST['WebsiteID'], PDO::PARAM_INT);
						$stmt->execute();			
						
						if($stmt->rowCount()){

							$usernameArray = array($temp['CreatedBy']);
							$useridArray = array();
							
							user_get_id_name($useridArray,$usernameArray);
							
							if($useridArray[0]!="NO_USERS" && $useridArray[0]!=""){
								add_points($useridArray[0],$WebsiteLinkPoints);
							}							
							
							$logstmt = $Gamedb->prepare("INSERT INTO `modlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
							$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
							$logstmt->bindValue(':link', $row['Website'], PDO::PARAM_STR);
							$logstmt->bindValue(':id', $_CLEANREQUEST['WebsiteID'], PDO::PARAM_INT);
							$logstmt->bindValue(':gameid', $row['ID'], PDO::PARAM_INT);
							$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
							$logstmt->execute();
							
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
							die('');
						}else{		
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
							die('');
						}		
					}else{
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');				
					}
				}else{	
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');
				}			
			}else if($_CLEANREQUEST['Protocol']=="AJAX"){
				$aResponse['error'] = 1;
				
				if($FDBPrivilages['MWLinks']==2 && isset($_CLEANREQUEST['WebsiteID'])){
					$stmt = $Gamedb->prepare("SELECT * from websites WHERE WebsiteID=:websiteid");
					$stmt->bindValue(':websiteid', $_CLEANREQUEST['WebsiteID'], PDO::PARAM_INT);
					$stmt->execute();
					
					if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						$stmt = $Gamedb->prepare("UPDATE websites SET `Pending`=0, `Flagged`=0, `FlaggedBy`='' WHERE WebsiteID=:websiteid");
						$stmt->bindValue(':websiteid', $_CLEANREQUEST['WebsiteID'], PDO::PARAM_INT);
						$stmt->execute();			
						
						if($stmt->rowCount()){

							$usernameArray = array($temp['CreatedBy']);
							$useridArray = array();
							
							user_get_id_name($useridArray,$usernameArray);
							
							if($useridArray[0]!="NO_USERS" && $useridArray[0]!=""){
								add_points($useridArray[0],$WebsiteLinkPoints);
							}							
							
							$logstmt = $Gamedb->prepare("INSERT INTO `modlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
							$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
							$logstmt->bindValue(':link', $row['Website'], PDO::PARAM_STR);
							$logstmt->bindValue(':id', $_CLEANREQUEST['WebsiteID'], PDO::PARAM_INT);
							$logstmt->bindValue(':gameid', $row['ID'], PDO::PARAM_INT);
							$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
							$logstmt->execute();
							
							$aResponse['error'] = 0;
						}else{		
							$aResponse['error'] = 1;
							$aResponse['errorMessage'] = "1";
						}		
					}else{
						$aResponse['error'] = 1;
						$aResponse['errorMessage'] = "2";
					}
				}else{	
					$aResponse['error'] = 1;
					$aResponse['errorMessage'] = "3";
				}				
				echo json_encode($aResponse);
			}
		break;
//------------------------------------------------------------------------------------------------------------------------------		
		case "AddWebLink":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['AWLinks']!= 0 && isset($_CLEANREQUEST['Website']) && isset($_CLEANREQUEST['Notes']) && isset($_CLEANREQUEST['ID']) ){																						
					$stmt = $Gamedb->prepare("INSERT INTO `websites`(`WebsiteID`, `ID`, `Website`, `Notes`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy`) VALUES (null,:id,:web,:notes,:pending,0,'',:User)");
					$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
					$stmt->bindValue(':web', $_CLEANREQUEST['Website'], PDO::PARAM_STR);
					$stmt->bindValue(':notes', $_CLEANREQUEST['Notes'], PDO::PARAM_STR);
					
					if($FDBPrivilages['AWLinks'] == 2){
						$stmt->bindValue(':pending', 0 , PDO::PARAM_INT);
						add_points($user->data['user_id'],$WebsiteLinkPoints);
					}else{
						$stmt->bindValue(':pending', 1 , PDO::PARAM_INT);
					}
					$stmt->bindValue(':User', $user->data['username'], PDO::PARAM_STR);
					$stmt->execute();
					
					if($stmt->rowCount()){
					
						$logstmt = $Gamedb->prepare("INSERT INTO `userlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
						$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
						$logstmt->bindValue(':link', $_CLEANREQUEST['Website'], PDO::PARAM_STR);
						$logstmt->bindValue(':id', $Gamedb->lastInsertId(), PDO::PARAM_INT);
						$logstmt->bindValue(':gameid', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
						$logstmt->execute();
					
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
						die('');
					}else{	
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');
					}
				}else{	
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');
				}	
			}		
		break;
//------------------------------------------------------------------------------------------------------------------------------		
//------------------------------------------------------------------------------------------------------------------------------		
		case "RestoreLink":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['RLinks']==2 && isset($_CLEANREQUEST['AutoID'])){
					$stmt = $Gamedb->prepare("INSERT INTO `downloadsbits`(`DownloadID`, `ID`, `OS`, `Version`, `Platform`, `FileSize`, `Download`, `Direct`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy`) SELECT `DownloadID`, `ID`, `OS`, `Version`, `Platform`, `FileSize`, `Download`, `Direct`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy` FROM `downloadsbits_bak` WHERE AutoID=:autoid");
					$stmt->bindValue(':autoid', $_CLEANREQUEST['AutoID'], PDO::PARAM_INT);
					$stmt->execute();	
					if($stmt->rowCount()){	
						$stmt = $Gamedb->prepare("SELECT * FROM `downloadsbits_bak` WHERE `AutoID`=:autoid");
						$stmt->bindValue(':autoid', $_CLEANREQUEST['AutoID'], PDO::PARAM_INT);
						$stmt->execute();
						
						if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							$logstmt = $Gamedb->prepare("INSERT INTO `modlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
							$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
							$logstmt->bindValue(':link', $row['Download'], PDO::PARAM_STR);
							$logstmt->bindValue(':id', $row['DownloadID'], PDO::PARAM_INT);
							$logstmt->bindValue(':gameid', $row['ID'], PDO::PARAM_INT);
							$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
							$logstmt->execute();
						}
											
						$stmt = $Gamedb->prepare("DELETE FROM `downloadsbits_bak` WHERE `AutoID`=:autoid");
						$stmt->bindValue(':autoid', $_CLEANREQUEST['AutoID'], PDO::PARAM_INT);
						$stmt->execute();
						
						if($stmt->rowCount()){																					
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
							die('');		
						}else{
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
							die('');				
						}
					}else{
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');				
					}					
				}else{
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');					
				}
			}
		break;
//------------------------------------------------------------------------------------------------------------------------------				
		case "DeleteLink":
			if($FDBPrivilages['DLinks']==2 && isset($_CLEANREQUEST['DownloadID'])){
				if($_CLEANREQUEST['Protocol']=="AJAX"){
					$aResponse['error'] = 1;
				}
				$stmt = $Gamedb->prepare("INSERT INTO `downloadsbits_bak`(`DownloadID`, `ID`, `OS`, `Version`, `Platform`, `FileSize`, `Download`, `Direct`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy`, `Operation`, `OperationUser`) SELECT `DownloadID`, `ID`, `OS`, `Version`, `Platform`, `FileSize`, `Download`, `Direct`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy`, 'Delete', :operationuser FROM `downloadsbits` WHERE DownloadID=:Downloadid");
				$stmt->bindValue(':Downloadid', $_CLEANREQUEST['DownloadID'], PDO::PARAM_INT);
				$stmt->bindValue(':operationuser', $user->data['username'], PDO::PARAM_STR);
				$stmt->execute();	

				$id = $Gamedb->lastInsertId();
				$stmt = $Gamedb->prepare("SELECT * FROM `downloadsbits_bak` WHERE DownloadID=:Downloadid");
				$stmt->bindValue(':Downloadid', $_CLEANREQUEST['DownloadID'], PDO::PARAM_INT);
				$stmt->execute();	
			
				if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$logstmt = $Gamedb->prepare("INSERT INTO `modlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
					$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
					$logstmt->bindValue(':link', $row['Download'], PDO::PARAM_STR);
					$logstmt->bindValue(':id', $id , PDO::PARAM_INT);
					$logstmt->bindValue(':gameid', $row['ID'], PDO::PARAM_INT);
					$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
					$logstmt->execute();
				}
			
				$stmt = $Gamedb->prepare("DELETE FROM `downloadsbits` WHERE DownloadID=:Downloadid");
				$stmt->bindValue(':Downloadid', $_CLEANREQUEST['DownloadID'], PDO::PARAM_INT);
				$stmt->execute();
				
				if($stmt->rowCount()){
					if($_CLEANREQUEST['Protocol']=="POST"){
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
						die('');
					}else if($_CLEANREQUEST['Protocol']=="AJAX"){
						$aResponse['error'] = 0;					
					}
				}else{	
					if($_CLEANREQUEST['Protocol']=="POST"){
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');
					}else if($_CLEANREQUEST['Protocol']=="AJAX"){
						$aResponse['error'] = 1;
						$aResponse['errorMessage'] = "1";					
					}
				}		
			}else{	
				if($_CLEANREQUEST['Protocol']=="POST"){
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');
				}else if($_CLEANREQUEST['Protocol']=="AJAX"){
					$aResponse['error'] = 1;
					$aResponse['errorMessage'] = "2";				
				}
			}

			if($_CLEANREQUEST['Protocol']=="AJAX"){
				echo json_encode($aResponse);
			}
			
			break;
//------------------------------------------------------------------------------------------------------------------------------		
		case "FlagLink":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['FLinks']==2  && isset($_CLEANREQUEST['DownloadID'])){
					$stmt = $Gamedb->prepare("UPDATE `downloadsbits` SET `Flagged`=1,`FlaggedBy`=:user WHERE `DownloadID`=:Downloadid");
					$stmt->bindValue(':Downloadid', $_CLEANREQUEST['DownloadID'], PDO::PARAM_INT);
					$stmt->bindValue(':user', $user->data['username'], PDO::PARAM_STR);
					$stmt->execute();
					
					if($stmt->rowCount()){
					
						$stmt = $Gamedb->prepare("SELECT * FROM `downloadsbits` WHERE `DownloadID`=:Downloadid");
						$stmt->bindValue(':Downloadid', $_CLEANREQUEST['DownloadID'], PDO::PARAM_INT);
						$stmt->execute();
						
						if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							$logstmt = $Gamedb->prepare("INSERT INTO `userlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
							$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
							$logstmt->bindValue(':link', $row['Download'], PDO::PARAM_STR);
							$logstmt->bindValue(':id', $_CLEANREQUEST['DownloadID'], PDO::PARAM_INT);
							$logstmt->bindValue(':gameid', $row['ID'], PDO::PARAM_INT);
							$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
							$logstmt->execute();
						}	
					
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
						die('');
					}else{	
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');
					}
				}else{	
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');
				}	
			}
		break;
//------------------------------------------------------------------------------------------------------------------------------		
		case "AcceptLink":
			if($FDBPrivilages['MLinks']==2 && isset($_CLEANREQUEST['DownloadID'])){				
				if($_CLEANREQUEST['Protocol']=="AJAX"){
					$aResponse['error'] = 1;
				}
				$stmt = $Gamedb->prepare("SELECT * from downloadsbits WHERE DownloadID=:Downloadid");
				$stmt->bindValue(':Downloadid', $_CLEANREQUEST['DownloadID'], PDO::PARAM_INT);
				$stmt->execute();
				
				if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$stmt = $Gamedb->prepare("UPDATE downloadsbits SET `Pending`=0, `Flagged`=0, `FlaggedBy`='' WHERE DownloadID=:Downloadid");
					$stmt->bindValue(':Downloadid', $_CLEANREQUEST['DownloadID'], PDO::PARAM_INT);
					$stmt->execute();			
					
					if($stmt->rowCount()){

						$usernameArray = array($temp['CreatedBy']);
						$useridArray = array();
						
						user_get_id_name($useridArray,$usernameArray);
						
						if($useridArray[0]!="NO_USERS" && $useridArray[0]!=""){
							add_points($useridArray[0],$DownloadLinkPoints);
						}	

						$logstmt = $Gamedb->prepare("INSERT INTO `modlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
						$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
						$logstmt->bindValue(':link', $row['Download'], PDO::PARAM_STR);
						$logstmt->bindValue(':id', $_CLEANREQUEST['DownloadID'], PDO::PARAM_INT);
						$logstmt->bindValue(':gameid', $row['ID'], PDO::PARAM_INT);
						$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
						$logstmt->execute();
						
						if($_CLEANREQUEST['Protocol']=="POST"){
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
							die('');
						}else if($_CLEANREQUEST['Protocol']=="AJAX"){
							$aResponse['error'] = 0;
						}
					}else{		
						if($_CLEANREQUEST['Protocol']=="POST"){
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
							die('');
						}else if($_CLEANREQUEST['Protocol']=="AJAX"){
							$aResponse['error'] = 1;
							$aResponse['errorMessage'] = "1";						
						}
					}		
				}else{
					if($_CLEANREQUEST['Protocol']=="POST"){
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');				
					}else if($_CLEANREQUEST['Protocol']=="AJAX"){
						$aResponse['error'] = 1;
						$aResponse['errorMessage'] = "2";						
					}
				}
			}else{	
				if($_CLEANREQUEST['Protocol']=="POST"){
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');
				}else if($_CLEANREQUEST['Protocol']=="AJAX"){
					$aResponse['error'] = 1;
					$aResponse['errorMessage'] = "3";				
				}
			}			
			
			if($_CLEANREQUEST['Protocol']=="AJAX"){
				echo json_encode($aResponse);
			}
					
			break;
//------------------------------------------------------------------------------------------------------------------------------		
		case "AddLink":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['ALinks']!=0 && isset($_CLEANREQUEST['Platform']) && isset($_CLEANREQUEST['Down']) && isset($_CLEANREQUEST['Ver']) && isset($_CLEANREQUEST['File']) && isset($_CLEANREQUEST['OS']) && isset($_CLEANREQUEST['Direct']) && isset($_CLEANREQUEST['ID']) ){																						
					$stmt = $Gamedb->prepare("INSERT INTO `downloadsbits`(`DownloadID`, `ID`, `OS`, `Version`, `Platform`, `FileSize`, `Download`, `Direct`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy`) VALUES (null,:id,:OS,:Ver,:Plat,:Size,:Down,:Direct,:pending,0,'',:User)");
					$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
					$stmt->bindValue(':OS', $_CLEANREQUEST['OS'], PDO::PARAM_STR);
					$stmt->bindValue(':Ver', $_CLEANREQUEST['Ver'], PDO::PARAM_STR);
					$stmt->bindValue(':Plat', $_CLEANREQUEST['Platform'], PDO::PARAM_INT);
					$stmt->bindValue(':Size', $_CLEANREQUEST['File'], PDO::PARAM_INT);
					$stmt->bindValue(':Down', $_CLEANREQUEST['Down'], PDO::PARAM_STR);
					$stmt->bindValue(':Direct', $_CLEANREQUEST['Direct'], PDO::PARAM_INT);
					
					if($FDBPrivilages['ALinks'] == 2){
						$stmt->bindValue(':pending', 0 , PDO::PARAM_INT);
						add_points($user->data['user_id'],$DownloadLinkPoints);
					}else{
						$stmt->bindValue(':pending', 1 , PDO::PARAM_INT);
					}
					$stmt->bindValue(':User', $user->data['username'], PDO::PARAM_STR);
					$stmt->execute();
					
					if($stmt->rowCount()){
						$logstmt = $Gamedb->prepare("INSERT INTO `userlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
						$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
						$logstmt->bindValue(':link', $_CLEANREQUEST['Down'], PDO::PARAM_STR);
						$logstmt->bindValue(':id', $Gamedb->lastInsertId(), PDO::PARAM_INT);
						$logstmt->bindValue(':gameid', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
						$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
						$logstmt->execute();	
					
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
						die('');
					}else{	
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');
					}
				}else{	
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');
				}	
			}
		break;
//------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------		
		case "AddReview":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['AReviews']!=0 && isset($_CLEANREQUEST['Review']) && ISSET($_CLEANREQUEST['Pros']) && ISSET($_CLEANREQUEST['Cons']) && ISSET($_CLEANREQUEST['Rate']) && ISSET($_CLEANREQUEST['ID'])){
					$stmt = $Gamedb->prepare("SELECT * FROM `reviews` WHERE `ID`=:id AND `Author`=:user");
					$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
					$stmt->bindValue(':user', $user->data['username'], PDO::PARAM_STR);
					$stmt->execute();
				
					if($stmt->rowCount()){	
						$stmt = $Gamedb->prepare("UPDATE `reviews` SET `Review`=:review,`Pros`=:pros,`Cons`=:cons,`Rating`=:rate, `Pending`=:pending WHERE `ID`=:id AND `Author`=:user");
						$stmt->bindValue(':review', $_CLEANREQUEST['Review'], PDO::PARAM_STR);
						$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$stmt->bindValue(':user', $user->data['username'], PDO::PARAM_STR);
						$stmt->bindValue(':pros', $_CLEANREQUEST['Pros'], PDO::PARAM_STR);
						$stmt->bindValue(':cons', $_CLEANREQUEST['Cons'], PDO::PARAM_STR);
						$stmt->bindValue(':rate', $_CLEANREQUEST['Rate'], PDO::PARAM_INT);
						$stmt->bindValue(':pending', '0', PDO::PARAM_INT);
						$stmt->execute();
					
						if($stmt->rowCount()){
						
							$logstmt = $Gamedb->prepare("INSERT INTO `userlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
							$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
							$logstmt->bindValue(':link', "Games/".$_CLEANREQUEST["ID"]."//", PDO::PARAM_STR);
							$logstmt->bindValue(':id', $Gamedb->lastInsertId(), PDO::PARAM_INT);
							$logstmt->bindValue(':gameid', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
							$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
							$logstmt->execute();	
							
							//Change Rating using GameScript again
							header( "Location: ".$DOMAIN."/GameScript.php?Action=RateGame&Protocol=POST&Return=".$_CLEANREQUEST['Return']."&EReturn=".$_CLEANREQUEST['EReturn']."&Rate=".$_CLEANREQUEST['Rate']."&ID=".$_CLEANREQUEST['ID']);
							die('');
						}else{
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
							die('');
						}
					}else{
						$stmt = $Gamedb->prepare("INSERT INTO `reviews`(`UniqueID`, `Review`, `ID`, `Author`, `Pros`, `Cons`, `Rating`, `Pending`, `Flagged`, `FlaggedBy`, `Date`) VALUES (null,:review,:id,:user,:pros,:cons,:rate,:pending,0,'',null)");
						$stmt->bindValue(':review', $_CLEANREQUEST['Review'], PDO::PARAM_STR);
						$stmt->bindValue(':id', $_CLEANREQUEST['ID'], PDO::PARAM_INT);
						$stmt->bindValue(':user', $user->data['username'], PDO::PARAM_STR);
						$stmt->bindValue(':pros', $_CLEANREQUEST['Pros'], PDO::PARAM_STR);
						$stmt->bindValue(':cons', $_CLEANREQUEST['Cons'], PDO::PARAM_STR);
						$stmt->bindValue(':rate', $_CLEANREQUEST['Rate'], PDO::PARAM_INT);
						if($FDBPrivilages['AReviews']==2){
							$stmt->bindValue(':pending', '0', PDO::PARAM_INT);
						}else{
							$stmt->bindValue(':pending', '1', PDO::PARAM_INT);
						}						
						$stmt->execute();
					
						if($stmt->rowCount()){	
						
							$logstmt = $Gamedb->prepare("INSERT INTO `userlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
							$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
							$logstmt->bindValue(':link', "", PDO::PARAM_STR);
							$logstmt->bindValue(':id', $Gamedb->lastInsertId(), PDO::PARAM_INT);
							$logstmt->bindValue(':gameid', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
							$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
							$logstmt->execute();	
						
							add_points($user->data['user_id'],$ReviewPoints);
							header( "Location: ".$DOMAIN."/GameScript.php?Action=RateGame&Protocol=POST&Return=".$_CLEANREQUEST['Return']."&EReturn=".$_CLEANREQUEST['EReturn']."&Rate=".$_CLEANREQUEST['Rate']."&ID=".$_CLEANREQUEST['ID']);
							die('');
						}else{
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
							die('');
						}
					}			
				}else{	
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');
				}	
			}
		break;
//------------------------------------------------------------------------------------------------------------------------------		
		case "RestoreReview":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['RReviews']==2 && isset($_CLEANREQUEST['AutoID'])){
					$stmt = $Gamedb->prepare("INSERT INTO `reviews`(`UniqueID`, `Review`, `ID`, `Author`, `Pros`, `Cons`, `Rating`, `Pending`, `Flagged`, `FlaggedBy`) SELECT `UniqueID`, `Review`, `ID`, `Author`, `Pros`, `Cons`, `Rating`, `Pending`, `Flagged`, `FlaggedBy` FROM `reviews_bak` WHERE `AutoID`=:autoid");
					$stmt->bindValue(':autoid', $_CLEANREQUEST['AutoID'], PDO::PARAM_INT);
					$stmt->execute();	
					
					
					if($stmt->rowCount()){		
						$stmt = $Gamedb->prepare("SELECT * FROM `reviews_bak` WHERE `AutoID`=:autoid");
						$stmt->bindValue(':autoid', $_CLEANREQUEST['AutoID'], PDO::PARAM_INT);
						$stmt->execute();
						
						if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							$logstmt = $Gamedb->prepare("INSERT INTO `modlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
							$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
							$logstmt->bindValue(':link', "", PDO::PARAM_STR);
							$logstmt->bindValue(':id', $row['UniqueID'], PDO::PARAM_INT);
							$logstmt->bindValue(':gameid', $row['ID'], PDO::PARAM_INT);
							$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
							$logstmt->execute();
						}
						$stmt = $Gamedb->prepare("DELETE FROM `reviews_bak` WHERE `AutoID`=:autoid");
						$stmt->bindValue(':autoid', $_CLEANREQUEST['AutoID'], PDO::PARAM_INT);
						$stmt->execute();
						
						if($stmt->rowCount()){																					
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
							die('');		
						}else{
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
							die('');				
						}
					}else{
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');				
					}					
				}else{
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');					
				}
			}
		break;
//------------------------------------------------------------------------------------------------------------------------------					
		case "DeleteReview":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if(isset($_CLEANREQUEST['UniqueID'])){
					$stmt = $Gamedb->prepare("SELECT * FROM `reviews` WHERE `UniqueID`=:id");
					$stmt->bindValue(':id', $_CLEANREQUEST['UniqueID'], PDO::PARAM_INT);
					$stmt->execute();
					if($row = $stmt->fetch(PDO::FETCH_ASSOC)){						
						if($FDBPrivilages['DReviews']==2 || $row['Author']==$user->data['username']){
						
							$stmt = $Gamedb->prepare("INSERT INTO `reviews_bak`(`UniqueID`, `Review`, `ID`, `Author`, `Pros`, `Cons`, `Rating`, `Pending`, `Flagged`, `FlaggedBy`, `Operation`, `OperationUser`) SELECT `UniqueID`, `Review`, `ID`, `Author`, `Pros`, `Cons`, `Rating`, `Pending`, `Flagged`, `FlaggedBy`, 'Delete', :operationuser FROM `reviews` WHERE `UniqueID`=:id");
							$stmt->bindValue(':id', $_CLEANREQUEST['UniqueID'], PDO::PARAM_INT);
							$stmt->bindValue(':operationuser', $user->data['username'], PDO::PARAM_STR);
							$stmt->execute();	
						
							$id = $Gamedb->lastInsertId();
							$stmt = $Gamedb->prepare("SELECT * FROM `reviews_bak` WHERE `UniqueID`=:id");
							$stmt->bindValue(':id', $_CLEANREQUEST['UniqueID'], PDO::PARAM_INT);
							$stmt->execute();	
						
							if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
								$logstmt = $Gamedb->prepare("INSERT INTO `modlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
								$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
								$logstmt->bindValue(':link', "", PDO::PARAM_STR);
								$logstmt->bindValue(':id', $id , PDO::PARAM_INT);
								$logstmt->bindValue(':gameid', $row['ID'], PDO::PARAM_INT);
								$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
								$logstmt->execute();
							}
						
							$stmt = $Gamedb->prepare("DELETE FROM `reviews` WHERE `UniqueID`=:id");
							$stmt->bindValue(':id', $_CLEANREQUEST['UniqueID'], PDO::PARAM_INT);
							$stmt->execute();						
							
							if($stmt->rowCount()){						
								header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
								die('');
							}else{
								header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
								die('');							
							}
						}else{
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
							die('');							
						}
					}else{	
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');
					}
				}				
			}
		break;
//------------------------------------------------------------------------------------------------------------------------------		
		case "AcceptReview":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['MReviews']==2 && isset($_CLEANREQUEST['UniqueID'])){
					$stmt = $Gamedb->prepare("SELECT * from reviews WHERE UniqueID=:uniqueid");
					$stmt->bindValue(':uniqueid', $_CLEANREQUEST['UniqueID'], PDO::PARAM_INT);
					$stmt->execute();
					
					if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						$stmt = $Gamedb->prepare("UPDATE reviews SET `Pending`=0, `Flagged`=0, `FlaggedBy`='' WHERE UniqueID=:uniqueid");
						$stmt->bindValue(':uniqueid', $_CLEANREQUEST['UniqueID'], PDO::PARAM_INT);
						$stmt->execute();			
						
						if($stmt->rowCount()){

							$usernameArray = array($row['Author']);
							$useridArray = array();
							
							user_get_id_name($useridArray,$usernameArray);
							
							if($useridArray[0]!="NO_USERS" && $useridArray[0]!=""){
								add_points($useridArray[0],$ReviewPoints);
							}							
							
							$logstmt = $Gamedb->prepare("INSERT INTO `modlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
							$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
							$logstmt->bindValue(':link', "Games/".$row['ID']."//", PDO::PARAM_STR);
							$logstmt->bindValue(':id', $_CLEANREQUEST['UniqueID'], PDO::PARAM_INT);
							$logstmt->bindValue(':gameid', $row['ID'], PDO::PARAM_INT);
							$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
							$logstmt->execute();
							
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
							die('');
						}else{		
							header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
							die('');
						}		
					}else{
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');				
					}
				}else{	
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');
				}			
		
			}
		break;
//------------------------------------------------------------------------------------------------------------------------------						
		case "FlagReview":
			if($_CLEANREQUEST['Protocol']=="POST"){
				if($FDBPrivilages['FReviews']==2  && isset($_CLEANREQUEST['UniqueID'])){
					$stmt = $Gamedb->prepare("UPDATE `reviews` SET `Flagged`=1,`FlaggedBy`=:user WHERE `UniqueID`=:uniqueid");
					$stmt->bindValue(':uniqueid', $_CLEANREQUEST['UniqueID'], PDO::PARAM_INT);
					$stmt->bindValue(':user', $user->data['username'], PDO::PARAM_STR);
					$stmt->execute();
					
					if($stmt->rowCount()){
					
						$stmt = $Gamedb->prepare("SELECT * FROM `reviews` WHERE `UniqueID`=:uniqueid");
						$stmt->bindValue(':uniqueid', $_CLEANREQUEST['UniqueID'], PDO::PARAM_INT);
						$stmt->execute();
						
						if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							$logstmt = $Gamedb->prepare("INSERT INTO `userlog`(`Operation`, `Link`, `ID`, `GameID`, `User`) VALUES (:action,:link,:id,:gameid,:username)");
							$logstmt->bindValue(':action', $_CLEANREQUEST['Action'], PDO::PARAM_STR);
							$logstmt->bindValue(':link', "Games/".$row['ID']."//", PDO::PARAM_STR);
							$logstmt->bindValue(':id', $_CLEANREQUEST['UniqueID'], PDO::PARAM_INT);
							$logstmt->bindValue(':gameid', $row['ID'], PDO::PARAM_INT);
							$logstmt->bindValue(':username', $user->data['username'], PDO::PARAM_STR);
							$logstmt->execute();
						}	
					
					
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['Return']);
						die('');
					}else{	
						header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
						die('');
					}
				}else{	
					header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
					die('');
				}	
			}
		break;		
//------------------------------------------------------------------------------------------------------------------------------				
		default:
			header( "Location: ".$DOMAIN."/".$_CLEANREQUEST['EReturn']);
			die('');
		break;
		
	}
	
	}else{
		//Return and EReturn not set
		header( "Location: index.php" );
	}
}

include("./Includes/FooterFunctions.php");
if($_CLEANREQUEST['Protocol']=="POST"){
	header( "Location: index.php" );
}
?>