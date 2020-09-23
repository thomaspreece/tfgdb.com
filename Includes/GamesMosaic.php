<?php

echo "<div style='margin-left:15px;margin-right:15px;overflow: auto;'>";
	echo "<h3>Random Picks</h3>";

	$stmt = $Gamedb->prepare("SELECT * FROM `freegames` ORDER BY RAND() LIMIT 0,16");
	$stmt->execute();	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {					
		$ResMid = "";
		$NoRes = true;
		$b = 4;
		do{
			$stmt2 = $Gamedb->prepare("SELECT * FROM resources WHERE ID=:id AND Type=:num");
			$stmt2->bindValue(':id', $row['ID'], PDO::PARAM_INT);
			$stmt2->bindValue(':num', $b, PDO::PARAM_INT);
			$stmt2->execute();							
		
			if($Resrow = $stmt2->fetch(PDO::FETCH_ASSOC)){
				$ResMid = $Resrow['ResMid'];
				$NoRes = false;
			}
			switch($b){
				case 1: 
					$NoRes = false;
					break;
				case 3:
					$b = 1;
					break;
				case 4:
					$b = 3;
					break;
			}
		}while($NoRes == true);

		if ($ResMid==null or $ResMid==""){
			$ResMid = "NoArtwork/NoShot.png";
		}
			
		if(strlen($row['About']) > 140){
			$row['About'] = substr($row['About'],0,140)."...";
		}

		
		echo "	
		
<div class='mosaic-block fadeMosaic'>
	<a style='text-decoration:none;' href='".$DOMAIN."/Games/".$row['ID']."/".$row['Name']."' class='mosaic-overlay'>
		<div class='mosaicdetails'>
			<h4>".$row['Name']."</h4>
			<p>".$row['About']."</p>
		</div>
	</a>
	<div class='mosaic-backdrop'><img width='215px' src='".artwork_correct($ResMid)."'/></div>
</div>		
		";
						
						
						
	}
echo "</div>";

?>