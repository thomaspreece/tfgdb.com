<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/Includes/HeaderFunctions.php");
	if($FDBPrivilages['MReviews']==2){
		$stmt = $Gamedb->prepare("SELECT * FROM reviews WHERE `Pending`=1 OR `Flagged`=1");
		$stmt->execute();	
		if($stmt->rowCount()==0){
			echo "
							<h3>No Reviews Pending Moderation</h3>
							<div class='news'>
								<div class='center' style='margin:15px;'>									
			";
		
		}else{
			echo "
							<h3>Reviews Pending Moderation</h3>
							<div class='news'>
								<div class='center' style='margin:15px;'>						
			";		
		}
		$IDarray = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$IDarray[] = $row['ID'];
		}
		$UniqueIDarray = array_unique($IDarray);
		
		foreach($UniqueIDarray as $temp){
			$stmt = $Gamedb->prepare("SELECT * FROM freegames WHERE `ID`=:id");
			$stmt->bindValue(':id', $temp, PDO::PARAM_INT);
			$stmt->execute();	
			if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			echo "
				<a target='_blank' href='Games/".$row['ID']."/".$row['Name']."/'>".$row['Name']."</a><br/>
			";
			}
		}
		

							
		echo "					
								</div>
							</div>	
		";
	}
?>	