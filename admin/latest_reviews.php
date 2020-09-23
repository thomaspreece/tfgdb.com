						<div>
														
<?php	
	require_once($_SERVER['DOCUMENT_ROOT']."/Includes/HeaderFunctions.php");
	$stmt = $Gamedb->prepare("SELECT * FROM reviews ORDER BY `UniqueID` DESC");
	$stmt->execute();	
	$RowCount = 0;
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$RowCount = $RowCount + 1;
		if($RowCount > 5){break;}
		echo "
						
						<div class='news'>
							<div style='margin-left:15px;margin-right:15px;margin-bottom:10px;'>
								<div class='center'>
								<a href='GetGame.php?ID=".$row['ID']."'>";
		$stmt2 = $Gamedb->prepare("SELECT * FROM freegames WHERE ID=:id");
		$stmt2->bindValue(':id', $row['ID'], PDO::PARAM_INT);
		$stmt2->execute();
		$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
		echo "
									".$row2['Name']."
								</a>
								<br/>
								</div>
								<table>
									<colgroup width='450px'></colgroup>
									<colgroup width='25px'></colgroup>
									<colgroup width='450px'></colgroup>
								<tr>
									<td id='BorderT1' class='AlignTop'>
										<div style='margin-left:20px;margin-right:20px;'>
										<b>Pros</b><br/>
										<pre style='width; 450px;font-family: Arial,sans-serif , \"Times New Roman\";font-size:14px;'>".$row['Pros']."</pre>
										</div>
									</td>
									<td></td>
									<td id='BorderT1' class='AlignTop'>
										<div style='margin-left:20px;margin-right:20px;'>
										<b>Cons</b><br/>
										<pre style='width; 450px;font-family: Arial,sans-serif , \"Times New Roman\";font-size:14px;'>".$row['Cons']."</pre>
										</div>
									</td>
								</tr>
								<tr>
									<td class='AlignTop' colspan='3'>
										<div style='margin-left:20px;margin-right:20px;margin-top:5px'>
											<b style='font-size:15px'>".$row['Rating']."/10</b>
											<pre style='width; 100%;font-family: Arial,sans-serif , \"Times New Roman\";font-size:14px;'>".$row['Review']."</pre>
											<div style='font-size:12px;color:#707070'>";
												if($row['Author']==$user->data['username'] || group_memberships(9,$user->data['user_id'],true)){
													echo "<a href='AddReviewScript.php?ID=".$row['ID']."&UniqueID=".$row['UniqueID']."'>(Delete)</a>";
												}
												echo "	
												Review by ".UsernameLink($row['Author'])." at ".$row['Date']." 
											</div>									
										</div>
									</td>
								</tr>
								</table>
							
							</div>
						</div>
		";
		
	}	
?>									
						</div>		
