						<div style='overflow: auto;'>
<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/Includes/HeaderFunctions.php");
	$stmt = $Gamedb->prepare("SELECT * FROM resources ORDER BY `ID` DESC LIMIT 20");
	$stmt->execute();	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$stmt2 = $Gamedb->prepare("SELECT * FROM freegames WHERE ID=:id");
		$stmt2->bindValue(':id', $row['ID'], PDO::PARAM_INT);
		$stmt2->execute();
		$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);			
		echo "
								<div style='margin:10px;float:left;'><a href='".$row['ResourcePath']."'><img src='".$row['ResThumb']."'/></a><br/><a href='GetGame.php?ID=".$row['ID']."'>".$row2['Name']."</a></div>
		";
			
	}
?>							
						</div>