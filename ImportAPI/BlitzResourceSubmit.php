<?php
$ip=$_SERVER['REMOTE_ADDR'];


if ($ip=="10.10.10.10"){
	include($_SERVER['DOCUMENT_ROOT']."/Includes/HeaderFunctions.php");


	//define a maxim size for the uploaded images in Kb
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
	//checks if the form has been submitted

	if(!isset($_POST['ID']) || !isset($_POST['ResType']) || !isset($_POST['Submit']) ){
		echo "Error
Missing Data";
		die('');
	}else{
		$ID = $_POST['ID'];
		$ResType = $_POST['ResType'];
	}

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
		if ($extension != "jpg"){
			//print error message
				echo "Error
Wrong Extension";
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
					echo "Error
Invalid Image dimensions";
					die('');
				}
			}
			if($ResType==5){
				if($width==760 && $height==140){

				}else{
					echo "Error
Invalid Image dimensions";
					die('');
				}
			}
			//compare the size with the maxim size we defined and print error if bigger
			if ($size > MAX_SIZE*1024){
						echo "Error
Invalid Image Size";
				die('');
			}else{

			//we will give an unique name, for example the time in unix time format
			$image_name=time().'.'.$extension;
			//the new name will be containing the full path where will be stored (images
			//folder)
			$newname="Artwork/".$image_name;
			$newname2="ArtworkThumb/".$image_name;
			$newname3="ArtworkMid/".$image_name;
			//we verify if the image has been uploaded, and print error instead
			$copied = copy($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT']."/".$newname);
			if (!$copied){
				echo "Error
image hasn't copied";
				die('');
			}
			}
		}
	}else{
		echo "Error
No Image";
		die('');
	}


	$image = new SimpleImage();
	$image->load($_SERVER['DOCUMENT_ROOT']."/".$newname);
	$image->resizeToWidth(200);
	$image->save($_SERVER['DOCUMENT_ROOT']."/".$newname2);

	$image->load($_SERVER['DOCUMENT_ROOT']."/".$newname);
	$image->resizeToHeight(350);
	$image->save($_SERVER['DOCUMENT_ROOT']."/".$newname3);


	//If no errors registred, print the success message
	 if(isset($_POST['Submit']))
	 {

		$stmt = $Gamedb->prepare("INSERT INTO `resources`(`ID`, `ResNumber`, `ResourcePath`, `ResThumb`, `ResMid`, `Type`, `Pending`, `Flagged`, `FlaggedBy`, `CreatedBy`) VALUES (:id , Null , :res , :resthumb , :resmid , :restype , 0 , 0 , '' , 'QuedTodaysGame' )");
		$stmt->bindValue(':id', $ID, PDO::PARAM_INT);
		$stmt->bindValue(':res', $newname, PDO::PARAM_INT);
		$stmt->bindValue(':resthumb', $newname2, PDO::PARAM_INT);
		$stmt->bindValue(':resmid', $newname3, PDO::PARAM_INT);
		$stmt->bindValue(':restype', $ResType, PDO::PARAM_INT);

		$stmt->execute();


		echo "";
		include($_SERVER['DOCUMENT_ROOT']."/Includes/FooterFunctions.php");
		die('');
	 }
}else{
	echo "Error
Unauthorised!
	<br/>";
	echo "User: ".$ip."<br/>";
	die('Unauthorised Access: Script Terminated');
}
 ?>
