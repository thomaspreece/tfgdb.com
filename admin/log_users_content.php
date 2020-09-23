<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/Includes/HeaderFunctions.php");
	if($FDBPrivilages['VULog']==2){
		$stmt = $Gamedb->prepare("SELECT * FROM `userlog` WHERE 1 ORDER BY `Date` DESC");
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
									<colgroup width='190px'></colgroup>
									<colgroup width='100px'></colgroup>
									<colgroup width='*'></colgroup>
									<colgroup width='80px'></colgroup>
									<colgroup width='100px'></colgroup>
									<colgroup width='150px'></colgroup>
								<tr style='font-weight:bold;'>
									<td>Date</td>
									<td>Operation</td>
									<td>Link</td>
									<td>ID</td>
									<td>Game Link</td>
									<td>User</td>
								</tr>	
		";
		$stmt = $Gamedb->prepare("SELECT * FROM `userlog` WHERE 1 ORDER BY `Date` DESC LIMIT ".(($CurrentPage-1)*$RowsPerPage).",".$RowsPerPage);
		$stmt->execute();	
		$colour = 0;
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$colour = $colour + 1;
			if($colour>=3) {$colour = 1;}		
			echo "
								<tr class='table_colour".$colour."'>
									<td>".$row['Date']."</td>
									<td>".$row['Operation']."</td>
									<td>
									";
									if($row['Link']!=""){
										if(strlen($row['Link'])>30){
											$rowlink = substr($row['Link'],0,30)."...";
										}else{
											$rowlink = $row['Link'];
										}
									echo "<a href='".$row['Link']."'>".$rowlink."</a>";
									}
									echo "
									</td>
									<td>".$row['ID']."</td>
									<td><a href='Games/".$row['GameID']."//'>".$row['GameID']."</a></td>
									<td>".UsernameLink($row['User'])."</td>
								</tr>
			";
		}
		
		echo "
								</table>
		";
	}
?>	