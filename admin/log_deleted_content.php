<?php	
	require_once($_SERVER['DOCUMENT_ROOT']."/Includes/HeaderFunctions.php");
	if($FDBPrivilages['RGame']==2){
		$stmt = $Gamedb->prepare("SELECT * FROM freegames_bak WHERE `Operation`='Delete'");
		$stmt->execute();
		
		if(ISSET($_GET['Page'])){
			$CurrentPage = intval($_GET['Page']);
		}else{
			$CurrentPage = 1;
		}
		
		$TotalRows = $stmt->rowCount();
		$RowsPerPage = 10;
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
									<colgroup width='*'></colgroup>
									<colgroup width='200px'></colgroup>
								<tr style='font-weight:bold;'>
									<td>Name</td>
									<td>Description</td>
									<td>By</td>
								</tr>	
		";

		$stmt = $Gamedb->prepare("SELECT * FROM freegames_bak WHERE `Operation`='Delete' ORDER BY `Date` DESC LIMIT ".(($CurrentPage-1)*$RowsPerPage).",".$RowsPerPage);
		$stmt->execute();
		$colour = 0;		
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$colour = $colour + 1;
			if($colour>=3) {$colour = 1;}			
			echo "
								<tr class='table_colour".$colour."'>
									<td>	
										<a href='GetGame.php?ID=".$row['ID']."'>".$row['Name']."</a>
									</td>
									<td>".$row['About']."</td>
									<td>
										Submitted By: ".UsernameLink($row['CreatedBy'])." || Last Edited By: ".UsernameLink($row['EditedBy'])." || Deleted By: ".UsernameLink($row['OperationUser'])."
									</td>
								</tr>
			";
		}
		
		echo "
								</table>
		";
	}
?>	