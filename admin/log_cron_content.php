<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/Includes/HeaderFunctions.php");
	if($FDBPrivilages['VCLog']==2){
		$stmt = $Gamedb->prepare("SELECT * FROM cronlog WHERE 1 ORDER BY `Date` DESC");
		$stmt->execute();
		
		if(ISSET($_GET['Page'])){
			$CurrentPage = intval($_GET['Page']);
		}else{
			$CurrentPage = 1;
		}
		
		$TotalRows = $stmt->rowCount();
		$RowsPerPage = 40;
		$Pages = ceil($TotalRows/$RowsPerPage);
		if($Pages < 1){
			$Pages = 1;
		}
		if($CurrentPage>$Pages){
			$CurrentPage=$Page;
		}else if($CurrentPage<1){
			$CurrentPage = 1;
		}
	
		echo "
								<table width='100%' class='LogTable'>
									<colgroup width='100px'></colgroup>
									<colgroup width='100px'></colgroup>
									<colgroup width='50px'></colgroup>
									<colgroup width='50px'></colgroup>
									<colgroup width='80px'></colgroup>
									<colgroup width='*'></colgroup>
									<colgroup width='*'></colgroup>
									<colgroup width='*'></colgroup>
								<tr style='font-weight:bold;'>
									<td>Date</td>
									<td>Operation</td>
									<td>Status</td>
									<td>Game</td>
									<td>Type</td>
									<td>Link</td>
									<td>Location</td>
									<td>Notes</td>
								</tr>	
		";
		$stmt = $Gamedb->prepare("SELECT * FROM cronlog WHERE 1 ORDER BY `Date` DESC LIMIT ".(($CurrentPage-1)*$RowsPerPage).",".$RowsPerPage);
		$stmt->execute();	
		$colour = 0;
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			if($row['Type']=="Website"){
				$stmt2 = $Gamedb->prepare("SELECT * FROM `websites` WHERE WebsiteID=:id");
				$stmt2->bindValue(':id', $row['LinkID'], PDO::PARAM_INT);
				$stmt2->execute();
				if($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
					$LinkText = $row2['Website'];
				}
			}else{
				$stmt2 = $Gamedb->prepare("SELECT * FROM `downloadsbits` WHERE DownloadID=:id");
				$stmt2->bindValue(':id', $row['LinkID'], PDO::PARAM_INT);
				$stmt2->execute();
				if($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
					$LinkText = $row2['Download'];
				}				
			}
			
			
			$colour = $colour + 1;
			if($colour>=3) {$colour = 1;}		
			echo "
								<tr class='table_colour".$colour."'>
									<td>".$row['Date']."</td>
									<td>".$row['Operation']."</td>
									<td>".$row['Status']."</td>
									<td><a href='Games/".$row['GameID']."//'>".$row['GameID']."</a></td>
									<td>".$row['Type']."</td>
									<td>";
									if($LinkText!=""){
										if(strlen($LinkText)>30){
											$rowlink = substr($LinkText,0,30)."...";
										}else{
											$rowlink = $LinkText;
										}
									echo "<a href='".$LinkText."'>".$rowlink."</a>";
									}
									echo "									
									</td>
									<td>
									";
									if($row['Location']!=""){
										if(strlen($row['Location'])>30){
											$rowlink = substr($row['Location'],0,30)."...";
										}else{
											$rowlink = $row['Location'];
										}
									echo "<a href='".$row['Location']."'>".$rowlink."</a>";
									}
									echo "
									</td>
									<td style='display:none;'>".$row['Notes']."</td>
									
								</tr>
			";
		}
		
		echo "
								</table>
		";
	}
?>	