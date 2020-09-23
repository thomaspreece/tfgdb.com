<?php
$PageName = "AddGame.php";
include("./Includes/HeaderFunctions.php");

$MetaDescription = "Add a new game to our database of free games";
$MetaKeywords = "Free,games,database,mac,windows,linux,genres,3D,2D";
$MetaTitle = "Add New Game - ".$DOMAINTITLE;
include("./Includes/Header.php");

if($FDBPrivilages['AGame'] == 2)
{


	echo "
	
	<table width='100%'>
		<colgroup width='20'></colgroup>
		<colgroup width='*'></colgroup>
		<colgroup width='20'></colgroup>
	<tr>
		<td colspan='5'><br/></td>
	</tr>
	<tr>
		
		<td></td>

		<td class='AlignTop'>
			<div class='center' style=\"margin:5px;\" >";
														
			echo "<h1>Submit New Game</h1>";
			if(isset($_GET["Error"])){
				if($_GET["Error"]=="UpdateFail"){
					echo "
					<div style='color:#FF0000;'>
						Failed to add game
					</div>";
				}
				if($_GET["Error"]=="GameExists"){
					echo "
					<div style='color:#FF0000;'>
						Game already exists
					</div>";
				}			
				if($_GET["Error"]=="UpdateSuccess"){
					echo "
					<div style='color:#00FF00;'>
						Game Successfully Added
					</div>";
				}									
				if($_GET["Error"]=="UpdatePending"){
					echo "
					<div style='color:#00FF00;'>
						Game Added, Links pending moderation
					</div>";
				}																	
			}								
			
			echo "
			</div>
			<div class='news'>
				<div style=\"margin:5px;\" >";

					echo "	
						
					
						<form id='GameDataForm' action='".$DOMAIN."/GameScript.php' method='post'>
						<input type='hidden' name='Action' value='AddGame'>
						<input type='hidden' name='Return' value='Edit/[ID]/[Name]/#WebsiteLinks'>
						<input type='hidden' name='EReturn' value='AddGame/?Error=Failed'>
						<input type='hidden' name='Protocol' value='POST'>				
						
						
						<table width='100%'>
							<colgroup width='70px'></colgroup>
							<colgroup width='*'></colgroup>
							<colgroup width='70px'></colgroup>
							<colgroup width='*'></colgroup>
							<colgroup width='70px'></colgroup>
							<colgroup width='*'></colgroup>
							<colgroup width='80px'></colgroup>
							<colgroup width='*'></colgroup>	
					
						<tr>
							<td>
								<b>Name:</b>
							</td>
							<td colspan='7'>
								<input style='width:100%;' type='text' class='validate[required,custom[namebox]]' data-prompt-position='topLeft' name='Name' value='".$GameInfo['Name']."' />
							</td>
						</tr>
						
						<tr>
							<td>
								<b>Description:</b>
							</td>
							<td colspan='7'>
								<textarea style='width:100%;Height:150px;' class='validate[required,custom[textboxs]]' data-prompt-position='topLeft' name='About' >".$GameInfo['About']."</textarea>
							</td>
						</tr>
						
						<tr>
							<td>
								<b>Genres: </b>
							</td>
							<td style='padding-right:10px;' colspan='7'>";
								
								
								echo "
								<table width=100%>
									
								<tr>
								";
								$TItemNum = -1;
								foreach($GAMEGENRESBITS as $temp){
								$TItemNum = $TItemNum + 1;
								if($TItemNum==4){
									$TItemNum = 0;
									echo "<tr></tr>";
								}
								echo "
									<td>
										<input type=checkbox class='validate[minCheckbox[1]]' name='genres[]' value='".$temp['ID']."'";
										if($GameInfo['GenreBITS'] & $temp['ID']){
											echo "checked='yes'";
										}
										echo "
										> ".$temp['Genre']."
									</td>
								";
								}
								
								
								
								
								
								
								echo "
								</tr>
								</table>
							</td>
						</tr>	
						
						<tr>
							<td colspan='8'>
							<div style='height:5px;'></div>
							</td>
						</tr>
							
						<tr>
							<td>
								<b>Studio:</b>
							</td>
							<td style='padding-right:10px;'>
							<select style='width:100%;' name='Studio' title='Was it orginally released as a commercial game by a big name publisher/developer? Otherwise its Indie.'>
								";
															
							
								foreach($GAMESTUDIOS as $temp){
									echo "<option value='".$temp['ID']."'";
									if($temp['ID']==$GameInfo['Studio']){
										echo " selected='Selected'";
									}
									echo ">".$temp['Studio']."</option>
									";
								}											
									
								echo "
							</select>
							</td>
							
							<td>
								<b>Type:</b>
							</td>
							<td style='padding-right:10px;'>
							<select style='width:100%;' name='Type'>
								";
												
								
								foreach($GAMETYPES as $temp){
									echo "<option value='".$temp['ID']."'";
									if($temp['ID']==$GameInfo['Type']){
										echo " selected='Selected'";
									}
									echo ">".$temp['Type']."</option>
									";
								}											
									
								echo "
							</select>
							
							</td>
							<td>
								<b>Source:</b>
							</td>
							<td style='padding-right:10px;'>
								<select style='width:100%;' name='Source' title='Can you download the source code and change or compile the game yourself, if so its open.'>
								";
																									
								
								foreach($GAMESOURCES as $temp){
									echo "<option value='".$temp['ID']."'";
									if($temp['ID']==$GameInfo['Source']){
										echo " selected='Selected'";
									}
									echo ">".$temp['Source']."</option>
									";
								}											
									
								echo "
								</select>										
							</td>
							<td>
								<b>Graphics:</b>
							</td>
							<td style='padding-right:10px;'>
							<select style='width:100%;' name='Graphics' title='If it looks like it could be played on a really old games console its Retro 2D, else choose 2D or 3D'>
								";
								
							
								foreach($GAMEGRAPHICS as $temp){
									echo "<option value='".$temp['ID']."'";
									if($temp['ID']==$GameInfo['Graphics']){
										echo " selected='Selected'";
									}
									echo ">".$temp['Graphics']."</option>
									";
								}											
									
								echo "
							</select>										
							</td>
						</tr>
						
						<tr>
							<td colspan='8'>
							<div style='height:5px;'></div>
							</td>
						</tr>
						
						<tr>
							
							
							<td>
								<b>Release:</b>
							</td>
							<td style='padding-right:10px;'>
							<select style='width:100%;' name='Release'>
								";
								
								foreach($GAMERELEASE as $temp){
									echo "<option value='".$temp['ID']."'";
									if($temp['ID']==$GameInfo['Release']){
										echo " selected='Selected'";
									}
									echo ">".$temp['Release']."</option>
									";
								}											
									
								echo "
							</select>
							
							</td>
							
							<td colspan='2'>
								
							</td>
							
							<td>
								<b>Age:</b>
							</td>
							<td style='padding-right:10px;'>
							<select style='width:100%;' name='Age'>
								";
								
								foreach($GAMEAGES as $temp){
									echo "<option value='".$temp['ID']."'";
									if($temp['ID']==$GameInfo['Age']){
										echo " selected='Selected'";
									}
									echo ">".$temp['Age']."</option>
									";
								}											
									
								echo "
							</select>
							
							</td>
							
							<td>
								<a name=\"Trail\" id=\"Trail\"><b>Trailer:</b></a>
							</td>
							<td style='padding-right:10px;'>
								<input style='width:100%;' type='text' name='Trail' class='validate[custom[trailer]]' data-prompt-position='bottomLeft' value='".$GameInfo['Trailer']."' />
							</td>
							
							
							
						</tr>
						
						<tr>
							<td colspan='8'>
							<div style='height:5px;'></div>
							</td>
						</tr>
						
						<tr>
							<td>
								<b>Modes: </b>
							</td>
							<td style='padding-right:10px;' colspan='7'>";
								
								
								echo "
								<table width=100%>
									<colgroup width=220px></colgroup>
									<colgroup width=220px></colgroup>
									<colgroup width=220px></colgroup>
									<colgroup width=220px></colgroup>
								<tr>
								
								
								
								";
								$TItemNum = -1;
								foreach($GAMEMODESBITS as $temp){
								$TItemNum = $TItemNum + 1;
								if($TItemNum==4){
									$TItemNum = 0;
									echo "<tr></tr>";
								}
								echo "
									<td>
										<input type=checkbox class='validate[minCheckbox[1]]' name='modes[]' value='".$temp['ID']."'";
										if($GameInfo['ModeBITS'] & $temp['ID']){
											echo "checked='yes'";
										}
										echo "
										> ".$temp['Mode']."
									</td>
								";
								}
								
								
								
								
								
								
								echo "
								</tr>
								</table>
							</td>
									
						</tr>
						
						<tr>
							<td colspan='8'>
							<div style='height:5px;'></div>
							</td>
						</tr>
						
						<tr>
							<td>
								<b>Platforms: </b>
							</td>
							<td style='padding-right:10px;' colspan='7'>";
								
								
								echo "
								<table width=100%>
									<colgroup width=220px></colgroup>
									<colgroup width=220px></colgroup>
									<colgroup width=220px></colgroup>
									<colgroup width=220px></colgroup>
								<tr>
								
								
								
								";
								$TItemNum = -1;
								foreach($GAMEPLATFORMSBITS as $temp){
								$TItemNum = $TItemNum + 1;
								if($TItemNum==4){
									$TItemNum = 0;
									echo "<tr></tr>";
								}
								echo "
									<td>
										<input type=checkbox class='validate[minCheckbox[1]]' name='platforms[]' value='".$temp['ID']."'";
										if($GameInfo['PlatformBITS'] & $temp['ID']){
											echo "checked='yes'";
										}
										echo "
										> ".$temp['Platform']."
									</td>
								";
								}
								
								
								
								
								
								
								echo "
								</tr>
								</table>
							</td>
						</tr>

						
						</table>
						
						
						<table width='100%'>
							<colgroup width='*'></colgroup>	
							<colgroup width='200px'></colgroup>
							<colgroup width='*'></colgroup>
						<tr>
							<td></td>
							<td>
								<input style='width:180px;' type='submit' value='Save' />	
							</td>
							<td></td>
						</tr>
						</table>
												 
						
						</form>	
					";
							
				
			echo "
				</div>
			</div>
		</td>
		<td></td>
	</tr>
	
	
	</table>";

}else{
	echo "
	<div class='news' >
		<div class='center' style=\"margin:5px;\" >
			You are either not logged in or don't have sufficient privilages to add games.<br/>
		</div>
	</div>
	
	";
}
include("./Includes/Footer.php");
include("./Includes/FooterFunctions.php");
?>
		