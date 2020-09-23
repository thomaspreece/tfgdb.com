<?php
$PageName = "Random.php";
include("./Includes/HeaderFunctions.php");
	
	// Select the first id in the target table
    $statement = $Gamedb->prepare("SELECT ID FROM freegames ORDER BY ID ASC LIMIT 1");
    $statement->execute();
    $lowest_id = $statement->fetch(PDO::FETCH_ASSOC);
 
    // Select the last id in the target table
    $statement = $Gamedb->prepare("SELECT ID FROM freegames ORDER BY ID DESC LIMIT 1");
    $statement->execute();
    $highest_id = $statement->fetch(PDO::FETCH_ASSOC);
 
    while(true)
    {
		// Generate a random integer
		$random_id = rand( $lowest_id['ID'], $highest_id['ID'] );
 
		// Check to see if the record exists
		$stmt = $Gamedb->prepare("SELECT * FROM freegames WHERE ID=:id");
		$stmt->bindValue(':id', $random_id , PDO::PARAM_INT);	  
		$stmt->execute();
		
		
 
      // If it exists, redirect to it
      if($row = $stmt->fetch(PDO::FETCH_ASSOC))
      {		
		header( "Location: ".$DOMAIN."/Games/".$row['ID']."/".$row['Name']."/" );
		die('');
      }
	  
	  $i++;
	  
	  if($i>40){
		die('Database Error - Random.php');  
	  }
 
    }


include("./Includes/FooterFunctions.php");
?>