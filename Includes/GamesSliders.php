<?php

//Slider JS Setup Code
echo "
<script>
$(document).ready(function(){ 
		$(\"div#Ratedslide\").slideViewerPro({
		thumbs: 3,
		autoslide: true,
		asTimer: 15000,
		typo: true,
		galBorderWidth: 0,
		thumbsBorderOpacity: 0,
		thumbsActiveBorderColor: \"grey\",
		buttonsWidth: 15,
		thumbsActiveBorderOpacity: 0.8,
		thumbsPercentReduction: 27,
		shuffle: true
		});
	});
	$(document).ready(function(){ 
		$(\"div#Randomslide\").slideViewerPro({
		thumbs: 3,
		autoslide: true,
		asTimer: 15000,
		typo: true,
		galBorderWidth: 0,
		thumbsBorderOpacity: 0,
		thumbsActiveBorderColor: \"grey\",
		buttonsWidth: 15,
		thumbsActiveBorderOpacity: 0.8,
		thumbsPercentReduction: 27,
		shuffle: true
		});
	});
	$(document).ready(function(){ 
		$(\"div#Addedslide\").slideViewerPro({
		thumbs: 3,
		autoslide: true,
		asTimer: 15000,
		typo: true,
		galBorderWidth: 0,
		thumbsBorderOpacity: 0,
		thumbsActiveBorderColor: \"grey\",
		buttonsWidth: 15,
		thumbsActiveBorderOpacity: 0.8,
		thumbsPercentReduction: 27,
		shuffle: true
		});
	});
</script>
";

echo "
	<table width='100%'>
		<colgroup width='*'></colgroup>
		<colgroup width='*'></colgroup>
		<colgroup width='*'></colgroup>
	<tr>
		<td>
		
			<h3>Top Rated</h3>
				<div id=\"Ratedslide\" class=\"svwp\" style='height:300px;'>
					<ul>";
				
					$stmt = $Gamedb->prepare("SELECT * FROM `freegames` ORDER BY `Rating` DESC LIMIT 0,10");
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
						echo "	
<li><a href='".$DOMAIN."/Games/".$row['ID']."/".$row['Name']."/'><img alt='".$row['Name']."  -  ".$row['Rating']."/10 (".$row['RateNum']." Ratings)' src='".artwork_correct($ResMid)."' style='height:250px;width:320px' /></a></li>
															";
						
					}

					
echo "
			</ul>	
	</div>
		</td>
<td>

			<h3>Random Picks</h3>
			<div id=\"Randomslide\" class=\"svwp\" style='height:300px;'>
					<ul>";
					
					$stmt = $Gamedb->prepare("SELECT * FROM `freegames` ORDER BY RAND() LIMIT 0,10");
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
						
						echo "	
<li><a href='".$DOMAIN."/Games/".$row['ID']."/".$row['Name']."/'><img alt='".$row['Name']."' src='".artwork_correct($ResMid)."' style='width:320px;height:250px;' /></a></li>
															";
						
						
						
					}

					
echo "
			</ul>
			</div>
			
		</td>
<td>
			<h3>Recently Added</h3>

			<div id=\"Addedslide\" class=\"svwp\" style='height:300px;'>
					<ul>";
					
					$stmt = $Gamedb->prepare("SELECT * FROM `freegames` ORDER BY `ID` DESC LIMIT 0,10");
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
						
						echo "	
<li><a href='".$DOMAIN."/Games/".$row['ID']."/".$row['Name']."/'><img alt='".$row['Name']."' src='".artwork_correct($ResMid)."' style='width:320px;height:250px;' /></a></li>
															";
						
					}

					
echo "
			</ul>
			</div>						
		</td>						


	</tr>
</table>
						";

?>