<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/Includes/HeaderFunctions.php");
	if($FDBPrivilages['MWLinks']==2){
		$websites = $Gamedb->prepare("SELECT * FROM websites WHERE `Pending`=1 OR `Flagged`=1");
		$websites->execute();	
		
		
		$colour = 0;		
		while($website = $websites->fetch(PDO::FETCH_ASSOC)) {

			$games = $Gamedb->prepare("SELECT * FROM freegames WHERE `ID`=:id");
			$games->bindValue(':id', $website['ID'], PDO::PARAM_INT);
			$games->execute();

			if($game = $games->fetch(PDO::FETCH_ASSOC)){
				$colour = $colour + 1;
				if($colour>=3) {$colour = 1;}
				echo "
		<table id='".$website['WebsiteID']."' width='100%' class='links_table table_colour".$colour."'>
			<colgroup width='*'></colgroup>
			<colgroup width='200px'></colgroup>
			<colgroup width='100px'></colgroup>
			<tr style='height:10px;'></tr>
			<tr>
				<td><a target='_blank' href='Edit/".$game['ID']."/".$game['Name']."/'>".$game['Name']."</a></td>
				<td>";
				if($website['Flagged']==1){
					echo "Flagged: ".UsernameLink($website['FlaggedBy']);
				}else{
					echo "By: ".UsernameLink($website['CreatedBy']);
				}
				echo "
				</td>
				<td rowspan='3'>
				<a href='#' onclick=\"getGameScript(".$website['WebsiteID'].",'AcceptWebLink');\">Approve</a>
				<br/>
				<br/>
				<a href='#' onclick=\"getGameScript(".$website['WebsiteID'].",'DeleteWebLink');\">Reject</a>
				</td>
			</tr>
			<tr><td colspan='2'>Website: <a href='".$website['Website']."'>Link</a></td></tr>
			<tr><td colspan='2'>Notes: ".$website['Notes']."</td></tr>
			<tr style='height:10px;'></tr>
				
		</table>";
			}else{
				$colour = $colour + 1;
				if($colour>=3) {$colour = 1;}
				echo "
		<table id='".$website['WebsiteID']."' width='100%' class='links_table table_colour".$colour."'>
			<colgroup width='*'></colgroup>
			<colgroup width='200px'></colgroup>
			<colgroup width='100px'></colgroup>
			<tr style='height:10px;'></tr>
			<tr>
				<td>Game No Longer Exists</td>
				<td>";
				if($website['Flagged']==1){
					echo "Flagged: ".UsernameLink($website['FlaggedBy']);
				}else{
					echo "By: ".UsernameLink($website['CreatedBy']);
				}
				echo "
				</td>
				<td rowspan='3'>
				
				<br/>
				<br/>
				<a href='#' onclick=\"getGameScript(".$website['WebsiteID'].",'DeleteWebLink');\">Reject</a>
				</td>
			</tr>
			<tr><td colspan='2'>Website: <a href='".$website['Website']."'>Link</a></td></tr>
			<tr><td colspan='2'>Notes: ".$website['Notes']."</td></tr>
			<tr style='height:10px;'></tr>
				
		</table>";
			}			
			
			
		}
		
		
		
	}
?>

<script>

	function getGameScript(ID,Action){
		switch (Action) {
			case "AcceptWebLink":
				$('table#'+ID).css({'background-color': '#FFA56D'});
				$.ajax({
					url: "/GameScript.php",
					type: "get",
					data: 'Protocol=AJAX&WebsiteID='+ID+'&Action='+Action,
					dataType: 'json',
					websiteID: ID,
					success: function(data){
						if(data.error=='0'){
							$('table#'+this.websiteID).css({'background-color': '#75FF75'});
							$('table#'+this.websiteID).fadeOut(1000);
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
			case "DeleteWebLink":
				$('table#'+ID).css({'background-color': '#FFA56D'});
				$.ajax({
					url: "/GameScript.php",
					type: "get",
					data: 'Protocol=AJAX&WebsiteID='+ID+'&Action='+Action,
					dataType: 'json',
					websiteID: ID,
					success: function(data){
						if(data.error=='0'){
							$('table#'+this.websiteID).css({'background-color': '#FF7575'});
							$('table#'+this.websiteID).fadeOut(1000);
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

