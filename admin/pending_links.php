<?php	
	$PageName = "pending_links.php";
	require_once($_SERVER['DOCUMENT_ROOT']."/Includes/HeaderFunctions.php");
	if($FDBPrivilages['MLinks']==2){
		$links = $Gamedb->prepare("SELECT * FROM downloadsbits WHERE `Pending`=1 OR `Flagged`=1");
		$links->execute();	
		
		
		$colour = 0;		
		while($link = $links->fetch(PDO::FETCH_ASSOC)) {

			$games = $Gamedb->prepare("SELECT * FROM freegames WHERE `ID`=:id");
			$games->bindValue(':id', $link['ID'], PDO::PARAM_INT);
			$games->execute();

			if($game = $games->fetch(PDO::FETCH_ASSOC)){
				$colour = $colour + 1;
				if($colour>=3) {$colour = 1;}
				$Platform = "";
				foreach($GAMEPLATFORMSBITS as $temp){
					if($temp['ID'] & $link['Platform']){
						$Platform = $Platform.$temp['Platform'].", ";
					}
				}
				$Platform = substr($Platform,0,-2);	
				echo "
		<table id='".$link['DownloadID']."' width='100%' class='links_table table_colour".$colour."'>
			<colgroup width='*'></colgroup>
			<colgroup width='200px'></colgroup>
			<colgroup width='100px'></colgroup>
			<tr style='height:10px;'></tr> 
			<tr>
				<td><a target='_blank' href='Edit/".$game['ID']."/".$game['Name']."/'>".$game['Name']."</a></td>
				<td>";
				if($link['Flagged']==1){
					echo "Flagged: ".UsernameLink($link['FlaggedBy']);
				}else{
					echo "By: ".UsernameLink($link['CreatedBy']);
				}
				
				echo "
				</td>
				<td rowspan='4'>
				<a href='#' onclick=\"getGameScript(".$link['DownloadID'].",'AcceptLink');\">Approve</a>
				<br/><br/>
				<a href='#' onclick=\"getGameScript(".$link['DownloadID'].",'DeleteLink');\">Reject</a>
				</td>
			</tr>
			<tr><td>Download: <a href='".$link['Download']."'>Link</a></td><td>Filesize: ".$link['FileSize']."</td></tr>
			<tr><td>Notes: ".$website['OS']."</td><td>Version: ".$link['Version']."</td></tr>
			<tr><td>Platform: ".$Platform."</td><td>Direct: ".$link['Direct']."</td></tr>
			<tr style='height:10px;'></tr>
			
		</table>";
			}else{
				$colour = $colour + 1;
				if($colour>=3) {$colour = 1;}
				$Platform = "";
				foreach($GAMEPLATFORMSBITS as $temp){
					if($temp['ID'] & $link['Platform']){
						$Platform = $Platform.$temp['Platform'].", ";
					}
				}
				$Platform = substr($Platform,0,-2);				
				echo "
		<table id='".$link['DownloadID']."' width='100%' class='links_table table_colour".$colour."'>
			<colgroup width='*'></colgroup>
			<colgroup width='200px'></colgroup>
			<colgroup width='100px'></colgroup>
			<tr style='height:10px;'></tr> 
			<tr>
				<td>Game No Longer Exists</td>
				<td>";
				if($link['Flagged']==1){
					echo "Flagged: ".UsernameLink($link['FlaggedBy']);
				}else{
					echo "By: ".UsernameLink($link['CreatedBy']);
				}
				
				echo "
				</td>
				<td rowspan='4'>
				
				<br/><br/>
				<a href='#' onclick=\"getGameScript(".$link['DownloadID'].",'DeleteLink');\">Reject</a>
				</td>
			</tr>
			<tr><td>Download: <a href='".$link['Download']."'>Link</a></td><td>Filesize: ".$link['FileSize']."</td></tr>
			<tr><td>Notes: ".$website['OS']."</td><td>Version: ".$link['Version']."</td></tr>
			<tr><td>Platform: ".$Platform."</td><td>Direct: ".$link['Direct']."</td></tr>
			<tr style='height:10px;'></tr>
			
		</table>";
			
			
			}			
			
			
		}
		
	
	}
?>	

<script>

	function getGameScript(ID,Action){
		switch (Action) {
			case "AcceptLink":
				$('table#'+ID).css({'background-color': '#FFA56D'});
				$.ajax({
					url: "/GameScript.php",
					type: "get",
					data: 'Protocol=AJAX&DownloadID='+ID+'&Action='+Action,
					dataType: 'json',
					downloadID: ID,
					success: function(data){
						if(data.error=='0'){
							$('table#'+this.downloadID).css({'background-color': '#75FF75'});
							$('table#'+this.downloadID).fadeOut(1000);
						}else{
							alert(data.errorMessage);
						}
					},
					error:function(){
						alert("error");
						alert(data.errorMessage);
					}   
				});
				break;
			case "DeleteLink":
				$('table#'+ID).css({'background-color': '#FFA56D'});
				$.ajax({
					url: "/GameScript.php",
					type: "get",
					data: 'Protocol=AJAX&DownloadID='+ID+'&Action='+Action,
					dataType: 'json',
					downloadID: ID,
					success: function(data){
						if(data.error=='0'){
							$('table#'+this.downloadID).css({'background-color': '#FF7575'});
							$('table#'+this.downloadID).fadeOut(1000);
						}else{
							alert(data.errorMessage);
						}
					},
					error:function(){
						alert("error");
						alert(data.errorMessage);
					}   
				});
				break;
		}
	}
</script>