						<div>
<?php	
	require_once($_SERVER['DOCUMENT_ROOT']."/Includes/HeaderFunctions.php");
	$stmt = $Gamedb->prepare("SELECT * FROM rating ORDER BY `UniqueID` DESC LIMIT 200");
	$stmt->execute();	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$stmt2 = $Gamedb->prepare("SELECT * FROM freegames WHERE ID=:id");
		$stmt2->bindValue(':id', $row['ID'], PDO::PARAM_INT);
		$stmt2->execute();
		$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);		
		echo "
								<a href='GetGame.php?ID=".$row['ID']."'>(".$row2['Name'].")</a> ".UsernameLink($row['User']).": ".$row['Rating']." <br/>
		";
	}
?>
						</div>
