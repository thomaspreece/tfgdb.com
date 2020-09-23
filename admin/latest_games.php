						<div>
<?php	
	require_once($_SERVER['DOCUMENT_ROOT']."/Includes/HeaderFunctions.php");
	$stmt = $Gamedb->prepare("SELECT * FROM freegames ORDER BY `ID` DESC LIMIT 5");
	$stmt->execute();	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		echo "
							<div class='news'>
								<div style='margin-left:15px;margin-right:15px;margin-bottom:10px;'>
									<div class='center'>
										<a href='GetGame.php?ID=".$row['ID']."'>".$row['Name']."</a>
									</div>
									<p>".$row['About']."</p>
									<div class='center'>
										Submitted By: ".UsernameLink($row['CreatedBy'])." || Last Edited By: ".UsernameLink($row['EditedBy'])."
									</div>
								</div>
							</div>
		";
	}
?>						
						</div>