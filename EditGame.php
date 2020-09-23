<?php
$PageName = "EditGame.php";
include("./Includes/HeaderFunctions.php");

$stmt = $Gamedb->prepare("SELECT * FROM freegames WHERE ID=:id");
$stmt->bindValue(':id', $_GET['ID'], PDO::PARAM_INT);
$stmt->execute();
$GameInfo = $stmt->fetch(PDO::FETCH_ASSOC);

if($GameInfo){
	$MetaDescription = $GameInfo['Name'].", ".$GameInfo['About'];
	$MetaTitle = "Edit: ".$GameInfo['Name']." - ".$DOMAINTITLE;
	if (substr($_SERVER['REQUEST_URI'],0,13)=="/EditGame.php"){
		header ('HTTP/1.1 301 Moved Permanently');
		header ("Location: ".$DOMAIN."/Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/");
		die();	
	}	
	
	$Graphics = "";
	$Source = "";
	$Studio = "";	
	$Type = "";
	$Release = "";
	
	foreach($GAMEGRAPHICS as $temp){
		if($temp['ID']==$GameInfo['Graphics']){
			$Graphics = $temp['Graphics'];
		}
	}
	
	foreach($GAMESOURCES as $temp){
		if($temp['ID']==$GameInfo['Source']){
			$Source = $temp['Source'];
		}	
	}	

	foreach($GAMESTUDIOS as $temp){
		if($temp['ID']==$GameInfo['Studio']){
			$Studio = $temp['Studio'];
		}	
	}			

	foreach($GAMETYPES as $temp){
		if($temp['ID']==$GameInfo['Type']){
			$Type = $temp['Type'];
		}	
	}		
	
	foreach($GAMERELEASE as $temp){
		if($temp['ID']==$GameInfo['Release']){
			$Release = $temp['Release'];
		}	
	}						
	
			
}else{
	$MetaDescription = "Cannot find the game you are looking for. Why not try some of our other games";
	$MetaTitle = "TheFreeGamesDB";		
}

$MetaKeywords = "Free,games,database,mac,windows,linux,genres,3D,2D";


include("./Includes/Header.php");
?>


	
	<?php
					if($FDBPrivilages['EGame'] == 2)
					{
						$ResFront = "";
						$ResBack = "";
						$ResBackGround = "";
						$ResScreen = "";
						$ResBanner = "";
						
						if($GameInfo){
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
								<div class='center' style=\"margin:5px;color: #FF0000;\" >";
									if(isset($_GET["Error"])){
										if($_GET["Error"]=='Saved'){
											echo "
											<div class='center' style=\"color: #00FF00;\" >
											Thank you for your information, an admin will review and update the game very soon.
											</div>";
										}
										if($_GET["Error"]=='EmptyFields'){
											echo "You Must Fill In BOTH Fields";
										}									
										if($_GET["Error"]=='UpdateSuccess'){
											echo "<div class='center' style=\"color: #00FF00;\" >
											Game Successfully Updated
											</div>";
										}
										if($_GET["Error"]=='UpdateFail'){
											echo "Update Failed";
										}		
										if($_GET["Error"]=='NoImage'){
											echo "Please select an image to upload";
										}
										if($_GET["Error"]=='ImageInvalid'){
											echo "Image is invalid, please make sure that it follows the specification listed below";
										}
										if($_GET["Error"]=='NoCopy'){
											echo "Server failed to copy file. Please contact an administrator about this problem";
										}		
										if($_GET["Error"]=='ImageSuccess'){
											echo "<div class='center' style=\"color: #00FF00;\" >
											Artwork uploaded successfully.
											</div>";
										}
										if($_GET["Error"]=='LinkPending'){
											echo "<div class='center' style=\"color: #00FF00;\" >
											Link submitted to moderator to check.
											</div>";
										}										
								
										
									}
								echo "</div>
								<div class='news'>
									<div style=\"margin:5px;\" >
									<form action='".$DOMAIN."/GameScript.php' id='GameDataForm' method='post'>
									<a href='".$DOMAIN."/Games/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/'><h1>".$GameInfo['Name']."</h1></a>
									<hr/>";
									
									if($FDBPrivilages["VULog"] == 2){
										echo "
										<table width='100%'>
										<tr>
										<td style='text-align:center'><b>Created By:</b> ".$GameInfo['CreatedBy']."</td>
										<td style='text-align:center'><b>Last Updated By:</b> ".$GameInfo['EditedBy']."</td>
										</tr>
										</table>
										<hr/>
										";
									}
									
									echo "
									<input type='hidden' name='ID' value='".$GameInfo["ID"]."'>
									<input type='hidden' name='Action' value='UpdateGame'>
									<input type='hidden' name='Return' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=GameUpdated'>	
									<input type='hidden' name='EReturn' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed'>
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
									";
									if($FDBPrivilages['NGame']==2){
										echo "
										<tr>
											<td>
												<b>Name:</b>
											</td>
											<td colspan='7'>
												<input style='width:100%;' class='validate[required,custom[namebox]]' data-prompt-position='topLeft' type='text' name='Name' value='".$GameInfo['Name']."' />
											</td>
										</tr>
										";
									}
									echo "
									<tr>
										<td colspan='8'>
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
												echo "</tr><tr>";
											}
											echo "
												<td>
													<input type=checkbox class='validate[minCheckbox[1]]' name='genres[]' value='".$temp['ID']."' ";
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
											<a name=\"Trail\" id=\"Trail\"><b>Youtube Trailer:</b></a>
										</td>
										<td style='padding-right:10px;'>
											<input style='width:100%;' type='text' name='Trail' class='validate[custom[trailer]]' data-prompt-position='bottomLeft' value='".$GameInfo['Trailer']."'/>
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
												echo "</tr><tr>";
											}
											echo "
												<td>
													<input type=checkbox class='validate[minCheckbox[1]]' name='modes[]' value='".$temp['ID']."' ";
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
												echo "</tr><tr>";
											}
											echo "
												<td>
													<input type=checkbox class='validate[minCheckbox[1]]' name='platforms[]' value='".$temp['ID']."' ";
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
									
									
									if($FDBPrivilages["RGame"] == 2){
										echo "
										<p class='center'><h3>Game Log</h3></p>
										<table width=100%>
											<colgroup width='150px'></colgroup>
											<colgroup width='40px'></colgroup>
											<colgroup width='100px'></colgroup>
											<colgroup width='*'></colgroup>
											<colgroup width='100px'></colgroup>
										<tr>
											<td><b>Date</b></td>
											<td><b>ID</b></td>
											<td><b>User</b></td>
											<td><b>Changes</b></td>
											<td><b>Restore</b></td>
										</tr>
										";
										$stmt = $Gamedb->prepare("SELECT * FROM freegames_bak WHERE ID=:id ORDER BY `Date` DESC");
										$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
										$stmt->execute();
										$resultsreturned = false;
										while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
											echo "
											<tr>
												<td>".$row['Date']."</td>
												<td>".$row['AutoID']."</td>
												<td>".$row['OperationUser']."</td>
												<td>
											";
											$AutoID = $row['AutoID'];
											unset($row['AutoID']);
											unset($row['Operation']);
											unset($row['OperationUser']);
											unset($row['Date']);
											$result = print_r(array_diff_assoc($row, $GameInfo),true);
											$result = str_replace("Array","",$result);
											$result = str_replace("
(
","",$result);
											$result = str_replace("
)","",$result);
											$result = str_replace("
","<br/>",$result);
											echo $result;
											echo "
												</td>
												<td><a href='".$DOMAIN."/GameScript.php?Action=RestoreGame&Protocol=POST&AutoID=".$AutoID."&ID=".$GameInfo['ID']."&EReturn=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed&Return=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/'>Restore</a></td>
											</tr>
											";
											$resultsreturned = true;
										}
										
										
										
										
										if($resultsreturned == false){
											echo "
											<tr>
												<td colspan=5 style='text-align:center;'>------------------------------------------------Nothing In Log------------------------------------------------</td>
											</tr>
											";
										}
										
										echo "
										</table>
										";
										
									}
									
									
									
									
									echo "
									<hr/>
																		 
									 <h3>Artwork</h3>
									<table width='100%'>
									<colgroup width='80px'></colgroup>
									<colgroup width='*'></colgroup>
									<colgroup width='100px'></colgroup>
									";
									 
									 $stmt = $Gamedb->prepare("SELECT * FROM resources WHERE ID=:id ORDER BY Type");
									$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
									$stmt->execute();
									
									while($Resrow = $stmt->fetch(PDO::FETCH_ASSOC)){
										echo "
										<tr>
											<td>
											";
											if($Resrow['Pending']==1){
												echo "Pending:";
											}elseif($Resrow['Flagged']==1){
												echo "Flagged:";
												if($FDBPrivilages['MResources']==2){
													echo "<br/>By ".$Resrow['FlaggedBy'];
												}
												
											}else{
												echo "Accepted:";
											}
											
											echo "<br/>";
											
											if($FDBPrivilages["MResources"] == 2){
												echo "(<a href='".$DOMAIN."/GameScript.php?Action=DeleteResource&Protocol=POST&Return=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/&EReturn=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed&ResID=".$Resrow['ResNumber']."'>Delete</a>) ";
											}else{
												if($Resrow['Flagged']==1 || $Resrow['Pending']==1){
													
												}else{
													if($FDBPrivilages["FResources"] == 2){
														echo "(<a href='".$DOMAIN."/GameScript.php?Action=FlagResource&Protocol=POST&Return=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/&EReturn=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed&ResourceID=".$Resrow['ResNumber']."'>Flag Link</a>) ";
													}
												}
											}
											
											if($FDBPrivilages["MResources"] == 2){
												if($Resrow['Pending']==1 || $Resrow['Flagged']==1){
													echo "(<a href='".$DOMAIN."/GameScript.php?Action=AcceptResource&Protocol=POST&Return=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/&EReturn=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed&ResourceID=".$Resrow['ResNumber']."'>Accept</a>)";
												}													
											}
											
											
											echo "
											</td>
											<td style='border-width:1px;border-style:solid;border-color:gray;'>
											<table width='100%'>
											<colgroup width='50px'></colgroup>
											<colgroup width=*></colgroup>
											
											
											<tr>
												<td style='padding-right:15px;'><b>Link:</b></td>
												";
												if($FDBPrivilages["MResources"] == 2){
													echo "<td style='padding-right:15px;'><div style='overflow:auto;'><a href='".artwork_correct($Resrow['ResourcePath'])."'>".$Resrow['ResourcePath']."</a></div></td>";
												}else{
													echo "<td style='padding-right:15px;'><div style='overflow:auto;'>".$Resrow['ResourcePath']."</div></td>";
												}
												echo "
												
												
												
											</tr>
											<tr>
												<td style='padding-right:15px;'><b>Type:</b></td>
												<td style='padding-right:15px;'>";
												if($Resrow['Type']==1){
													echo "Front Cover";
												}elseif($Resrow['Type']==2){
													echo "Back Cover";
												}elseif($Resrow['Type']==3){
													echo "Fan Art";
												}elseif($Resrow['Type']==4){
													echo "ScreenShot";
												}elseif($Resrow['Type']==5){
													echo "Banner";
												}
												echo "
												</td>
											
												
												
											</tr>";
											if($FDBPrivilages["MResources"] == 2){
												echo "
											<tr>
												<td style='padding-right:15px;'><b>User:</b></td>
												<td style='padding-right:15px;'>".$Resrow['CreatedBy']."</td>
											</tr>
												";
											}
											echo "
											</table>
											</td>
											<td><img width='100px' src='".artwork_correct($Resrow['ResThumb'])."' /></td>
										</tr>	
										
										";
									}
									 
									echo "</table>		

									<a name=\"Art\" id=\"Artwork\"><p class='center'><h3>Add Artwork</h3></p></a>
									<p>
										Maximum file size is <b>1500kb</b> (appx. 1.5mb) and file format must be <b>.jpg</b>, <b>.jpeg</b>, <b>.png</b>, <b>.gif</b><br/>
										Fanart must be of size <b>1920x1080 or 1280x720</b> and 
										Banners must be of size <b>760x140</b><br/>
										Please only upload quality artwork, we do not accept pixelated or poor quality artwork!
									</p>
									<table width='100%'>
										<colgroup width='120px'></colgroup>
										<colgroup width='*'></colgroup>
									<tr>
										<td>Front Cover:</td>
										<td>
										 <form name='newad' method='post' enctype='multipart/form-data' action='".$DOMAIN."/GameScript.php'>
										 <input type='hidden' name='ID' value='".$GameInfo["ID"]."'>
										 <input type='hidden' name='Action' value='AddResource'>
										 <input type='hidden' name='Protocol' value='POST'>
										 <input type='hidden' name='EReturn' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed'>
										 <input type='hidden' name='Return' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/'>										 
										 <input type='hidden' name='ResType' value='1'>
										 <input type='file' name='image'><input name='Submit' type='submit' value='Upload image'>
										 </form>
										 </td>
									 </tr>
									 <tr>
										<td>Back Cover:</td>
										<td>
										 <form name='newad' method='post' enctype='multipart/form-data' action='".$DOMAIN."/GameScript.php'>
										 <input type='hidden' name='ID' value='".$GameInfo["ID"]."'>
										 <input type='hidden' name='Action' value='AddResource'>
										 <input type='hidden' name='Protocol' value='POST'>
										 <input type='hidden' name='EReturn' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed'>
										 <input type='hidden' name='Return' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/'>										 
										 <input type='hidden' name='ResType' value='2'>
										 <input type='file' name='image'><input name='Submit' type='submit' value='Upload image'>
										 </form>
										 </td>
									 </tr>
									<tr>
										<td>Fanart:</td>
										<td>
										 <form name='newad' method='post' enctype='multipart/form-data' action='".$DOMAIN."/GameScript.php'>
										 <input type='hidden' name='ID' value='".$GameInfo['ID']."'>
										 <input type='hidden' name='Action' value='AddResource'>
										 <input type='hidden' name='Protocol' value='POST'>
										 <input type='hidden' name='EReturn' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed'>
										 <input type='hidden' name='Return' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/'>
										 <input type='hidden' name='ResType' value='3'>
										 <input type='file' name='image'><input name='Submit' type='submit' value='Upload image'>
										 </form>
										 </td>
									 </tr>
									<tr>
										<td>ScreenShots:</td>
										<td>
										 <form name='newad' method='post' enctype='multipart/form-data' action='".$DOMAIN."/GameScript.php'>
										 <input type='hidden' name='ID' value='".$GameInfo['ID']."'>
										 <input type='hidden' name='Action' value='AddResource'>
										 <input type='hidden' name='Protocol' value='POST'>
										 <input type='hidden' name='EReturn' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed'>
										 <input type='hidden' name='Return' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/'>										 
										 <input type='hidden' name='ResType' value='4'>
										 <input type='file' name='image'><input name='Submit' type='submit' value='Upload image'>
										 </form>
										 </td>
									 </tr>
									<tr>
										<td>Banner:</td>
										<td>
										 <form name='newad' method='post' enctype='multipart/form-data' action='".$DOMAIN."/GameScript.php'>
										 <input type='hidden' name='ID' value='".$GameInfo['ID']."'>
										 <input type='hidden' name='Action' value='AddResource'>
										 <input type='hidden' name='Protocol' value='POST'>
										 <input type='hidden' name='EReturn' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed'>
										 <input type='hidden' name='Return' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/'>										 
										 <input type='hidden' name='ResType' value='5'>
										 <input type='file' name='image'><input name='Submit' type='submit' value='Upload image'>
										 </form>
										 </td>
									 </tr>								 
									 </table>
									";
									if($FDBPrivilages["RResources"] == 2){
										echo "
										<p class='center'><h3>Artwork Log</h3></p>
										<table width='100%'>
										<colgroup width='150px'></colgroup>
										<colgroup width='40px'></colgroup>
										<colgroup width='100px'></colgroup>
										<colgroup width=*></colgroup>
										<colgroup width='100px'></colgroup>
										<colgroup width='100px'></colgroup>
										<colgroup width='100px'></colgroup>
											<tr>
												<td><b>Date</b></td>
												<td><b>ID</b></td>
												<td><b>Operation</b></td>
												<td><b>Link</b></td>
												<td><b>Type</b></td>
												<td><b>Restore</b></td>
												<td><b>Image</b></td>
											</tr>
										";
										$stmt = $Gamedb->prepare("SELECT * FROM resources_bak WHERE ID=:id ORDER BY `Date` DESC");
										$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
										$stmt->execute();
										$resultsreturned = false;
										while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
											echo "
											<tr>
												<td>".$row['Date']."</td>
												<td>".$row['AutoID']."</td>
												<td>".$row['Operation']."</td>
												<td><a href='".artwork_correct($row['ResourcePath'])."'>".$row['ResourcePath']."</a></td>
												<td>";
												if($row['Type']==1){
													echo "Front Cover";
												}elseif($row['Type']==2){
													echo "Back Cover";
												}elseif($row['Type']==3){
													echo "Fan Art";
												}elseif($row['Type']==4){
													echo "ScreenShot";
												}elseif($row['Type']==5){
													echo "Banner";
												}
												echo "
												</td>
												<td><a href='".$DOMAIN."/GameScript.php?Action=RestoreResource&Protocol=POST&AutoID=".$row['AutoID']."&EReturn=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed&Return=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/'>Restore</a></td>
												<td><img width='100px' src='".artwork_correct($row['ResThumb'])."' /></td>
											</tr>
											";
											$resultsreturned = true;
										}
										
										if($resultsreturned == false){
											echo "
											<tr>
												<td colspan='7' style='text-align:center;'>------------------------------------------------Nothing In Log------------------------------------------------</td>
											</tr>
											";
										}
										echo "
										</table>
										";
										
									}
									echo "
									 <hr/>
										
									<a name='WebsiteLinks'><h3>Website Links</h3></a>
									<table width='100%'>
									<colgroup width='80px'></colgroup>
									<colgroup width='*'></colgroup>
									";
									
									$stmt = $Gamedb->prepare("SELECT * FROM websites WHERE ID=:id");
									$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
									$stmt->execute();
									
									
									while($Webrow = $stmt->fetch(PDO::FETCH_ASSOC)){
										echo "
										<tr>
											<td>
											";
											if($Webrow['Pending']==1){
												echo "Pending:";
											}elseif($Webrow['Flagged']==1){
												echo "Flagged:";
												if($FDBPrivilages['MWLinks']==2){
													echo "<br/>By ".$Webrow['FlaggedBy'];
												}
												
											}else{
												echo "Accepted:";
											}
											
											echo "<br/>";
											
											if($FDBPrivilages["MWLinks"] == 2){
												if($Webrow['Pending']==1 || $Webrow['Flagged']==1){
													echo "(<a href='".$DOMAIN."/GameScript.php?Action=AcceptWebLink&Protocol=POST&Return=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/&EReturn=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed&WebsiteID=".$Webrow['WebsiteID']."'>Accept</a>)";
												}													
											}
											
											
											
											if($FDBPrivilages["MWLinks"] == 2){
												echo "(<a href='".$DOMAIN."/GameScript.php?Action=DeleteWebLink&Protocol=POST&Return=Edit/".$GameInfo['ID']."/".urlencode(urlencode($GameInfo['Name']))."/&EReturn=Edit/".$GameInfo['ID']."/".urlencode(urlencode($GameInfo['Name']))."/?Error=Failed&WebsiteID=".$Webrow['WebsiteID']."'>Delete</a>)";
											}else{
												if ($Webrow['Flagged']==1 || $Webrow['Pending']==1){
													
												}else{
													if($FDBPrivilages["FWLinks"] == 2){
														echo "(<a href='".$DOMAIN."/GameScript.php?Action=FlagWebLink&Protocol=POST&Return=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/&EReturn=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed&WebsiteID=".$Webrow['WebsiteID']."'>Flag Link</a>)";
													}
												}
											}
											
											
											
											
											echo "
											</td>
											<td style='border-width:1px;border-style:solid;border-color:gray;'>
											<table width='100%'>
											<colgroup width='50px'></colgroup>
											<colgroup width=*></colgroup>
											
											<tr>
												<td><b>Link:</b></td>";
												
												if($FDBPrivilages["MWLinks"] == 2){
													echo "<td><div style='width:730px;overflow:auto;'><a href='".$Webrow['Website']."'>".$Webrow['Website']."</a></div></td>";
												}else{
													echo "<td><div style='width:730px;overflow:auto;'>".$Webrow['Website']."</div></td>";
												}
												
												
												echo "
												
											</tr>
											<tr>
												<td><b>Notes:</b></td>
												<td><div style='width:730px;overflow:auto;'>".$Webrow['Notes']."</div></td>
											</tr>";
											if($FDBPrivilages["MWLinks"] == 2){
												echo "
											<tr>
												<td style='padding-right:15px;'><b>User:</b></td>
												<td style='padding-right:15px;'>".$Webrow['CreatedBy']."</td>
											</tr>
												";
											}
											echo "
											</table>
											</td>
										</tr>																			
										";
									}
									echo "</table>";										
										echo "
										<a name='AddWebsiteLinks'><h3>Add A Website</h3></a>
										<form id='WebLinkForm' method='post' action='".$DOMAIN."/GameScript.php'>
											<input type='hidden' name='ID' value='".$GameInfo['ID']."'>										
											<input type='hidden' name='Action' value='AddWebLink'>		
											<input type='hidden' name='Protocol' value='POST'>	
											<input type='hidden' name='Return' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/'>	
											<input type='hidden' name='EReturn' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed'>	
											
											<table width='100%'>
												<colgroup width='80px'></colgroup>
												<colgroup width='*'></colgroup>
																							
											<tr>
												<td><b>Website:</b></td>
												<td colspan='3'>
												<input style='width:100%;' class='validate[required,custom[url]]' data-prompt-position='topLeft' type='text' name='Website' value=''  />
												</td>
																							
												
											</tr>
											<tr>
												<td><b>Notes:</b></td>
												<td>
													<input style='width:100%;' class='validate[custom[downloadnotes]]' data-prompt-position='bottomLeft' type='text' name='Notes' value=''/>
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
									
									if($FDBPrivilages["RWLinks"] == 2){
										echo "
										<p class='center'><h3>Website Log</h3></p>
										<table width='100%'>
										<colgroup width='150px'></colgroup>
										<colgroup width='40px'></colgroup>
										<colgroup width='100px'></colgroup>
										<colgroup width=*></colgroup>
										<colgroup width=*></colgroup>
										<colgroup width='100px'></colgroup>
										
											<tr>
												<td><b>Date</b></td>
												<td><b>ID</b></td>
												<td><b>Operation</b></td>
												<td><b>Link</b></td>
												<td><b>Notes</b></td>
												<td><b>Restore</b></td>
												
											</tr>
										";
										$stmt = $Gamedb->prepare("SELECT * FROM websites_bak WHERE ID=:id ORDER BY `Date` DESC");
										$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
										$stmt->execute();
										$resultsreturned = false;
										while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
											echo "
											<tr>
												<td>".$row['Date']."</td>
												<td>".$row['AutoID']."</td>
												<td>".$row['Operation']."</td>
												<td><a href='".$row['Website']."'>".$row['Website']."</a></td>
												<td>".$row['Notes']."</td>
												<td><a href='".$DOMAIN."/GameScript.php?Action=RestoreWebLink&Protocol=POST&AutoID=".$row['AutoID']."&EReturn=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed&Return=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/'>Restore</a></td>
												
											</tr>
											";
											$resultsreturned = true;
										}
										if($resultsreturned == false){
											echo "
											<tr>
												<td colspan='6' style='text-align:center;'>------------------------------------------------Nothing In Log------------------------------------------------</td>
											</tr>
											";
										}
										
										echo "
										</table>
										";
										
									}
									
									
									echo "
									<hr/>";
									$NoPlatforms = true; 
									foreach($GAMEPLATFORMSBITS as $temp){
										if($temp['DownloadAllowed']==1 && $GameInfo['PlatformBITS'] & $temp['ID']){
											$NoPlatforms = false; 
										}
									}
									if($NoPlatforms == false){
									
										echo "
										<a name='DownloadLinks'><h3>Download Links</h3></a>
										<table width='100%'>
										<colgroup width='80px'></colgroup>
										<colgroup width='*'></colgroup>
										";
										
										$stmt = $Gamedb->prepare("SELECT * FROM downloadsbits WHERE ID=:id ORDER BY Platform");
										$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
										$stmt->execute();
										
										
										while($Downrow = $stmt->fetch(PDO::FETCH_ASSOC)){
											echo "
											<tr>
												<td>
												";
												if($Downrow['Pending']==1){
													echo "Pending:";
												}elseif($Downrow['Flagged']==1){
													echo "Flagged:";
													if($FDBPrivilages['MLinks']==2){
														echo "<br/>By ".$Downrow['FlaggedBy'];
													}
													
												}else{
													echo "Accepted:";
												}
												
												echo "<br/>";
												
												if($FDBPrivilages["MLinks"] == 2){
													echo "(<a href='".$DOMAIN."/GameScript.php?Action=DeleteLink&Protocol=POST&Return=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/&EReturn=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed&DownloadID=".$Downrow['DownloadID']."'>Delete</a>) ";
												}else{
													if($Downrow['Flagged']==1 || $Downrow['Pending']==1){
														
													}else{
														if($FDBPrivilages["FLinks"] == 2){
															echo "(<a href='".$DOMAIN."/GameScript.php?Action=FlagLink&Protocol=POST&Return=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/&EReturn=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed&DownloadID=".$Downrow['DownloadID']."'>Flag Link</a>) ";
														}
													}
												}
												
												if($FDBPrivilages["MLinks"] == 2){
													if($Downrow['Pending']==1 || $Downrow['Flagged']==1){
														echo "(<a href='".$DOMAIN."/GameScript.php?Action=AcceptLink&Protocol=POST&Return=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/&EReturn=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed&DownloadID=".$Downrow['DownloadID']."'>Accept</a>)";
													}													
												}
												
												
												echo "
												</td>
												<td style='border-width:1px;border-style:solid;border-color:gray;'>
												<table width='100%'>
												<colgroup width='50px'></colgroup>
												<colgroup width=*></colgroup>
												<colgroup width='50px'></colgroup>
												<colgroup width='80px'></colgroup>
												<colgroup width='50px'></colgroup>
												<colgroup width='130px'></colgroup>
												
												<tr>
													<td style='padding-right:15px;'><b>Notes:</b></td>
													<td style='padding-right:15px;'><div style='width:450px;overflow:auto;'>".$Downrow['OS']."</div></td>
													<td style='padding-right:15px;'><b>Type:</b></td>
													<td style='padding-right:15px;'>";
													if($Downrow['Direct']==1){
														echo "Direct";
													}else{
														echo "Indirect";
													}
													echo 
													"</td> 
													<td style='padding-right:15px;'>
													<b>Plat:</b> 
													</td>
													<td style='padding-right:15px;'>
													";
													foreach($GAMEPLATFORMSBITS as $temp){
														if($Downrow['Platform']==$temp['ID']){
															echo $temp['Platform'];
														}
													}
													echo "
													</td>
													
												</tr>
												<tr>
													<td style='padding-right:15px;'><b>Link:</b></td>";
													if($FDBPrivilages["MLinks"] == 2){
														echo "<td style='padding-right:15px;'><div style='width:450px;overflow:auto;'><a href='".$Downrow['Download']."'>".$Downrow['Download']."</a></div></td>";
													}else{
														echo "<td style='padding-right:15px;'><div style='width:450px;overflow:auto;'>".$Downrow['Download']."</div></td>";
													}
													echo "
													
													<td style='padding-right:15px;'><b>Version:</b></td>
													<td style='padding-right:15px;'>".$Downrow['Version']."</td>
													<td style='padding-right:15px;'><b>Size:</b></td>
													<td style='padding-right:15px;'>".round($Downrow['FileSize'],2)."</td>
													
												</tr>";
												if($FDBPrivilages["MLinks"] == 2){
													echo "
												<tr>
													<td style='padding-right:15px;'><b>User:</b></td>
													<td style='padding-right:15px;' colspan='5'>".$Downrow['CreatedBy']."</td>
												</tr>
													";
												}
												echo "
												</table>
												</td>
											</tr>																			
											";
										}
										echo "</table>";	
										if($FDBPrivilages["ALinks"] != 0){
								
											echo "
											<a name='AddDownloadLinks'><h3>Add A Download Link</h3></a>
											<form method='post' id='LinkForm' action='".$DOMAIN."/GameScript.php'>
												<input type='hidden' name='ID' value='".$GameInfo['ID']."'>										
												<input type='hidden' name='Action' value='AddLink'>												
												<input type='hidden' name='Protocol' value='POST'>	
												<input type='hidden' name='Return' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/'>	
												<input type='hidden' name='EReturn' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed'>	
												
												<table width='100%'>
													<colgroup width='80px'></colgroup>
													<colgroup width='*'></colgroup>
													<colgroup width='70px'></colgroup>
													<colgroup width='*'></colgroup>
													<colgroup width='70px'></colgroup>
													<colgroup width='*'></colgroup>
													<colgroup width='70px'></colgroup>
													<colgroup width='*'></colgroup>												
												<tr>
													<td style='padding-right:15px;'><b>Download:</b></td>
													<td style='padding-right:15px;' colspan='3'>
													<input style='width:100%;' type='text' class='validate[required,custom[downloadurl]]' name='Down' value='' data-prompt-position='topLeft' />
													</td>
													<td style='padding-right:15px;'><b>Notes:</b></td>
													<td style='padding-right:15px;' colspan='3'>
													<input style='width:100%;' type='text' class='validate[maxSize[100],custom[downloadnotes]]' data-prompt-position='topLeft' maxlength='100' name='OS' value='' title='What versions of the platform is it compatable with. Eg: Win 7/Vista/XP' />
													</td>												
													
												</tr>
												<tr>
													<td style='padding-right:15px;'><b>Platform:</b></td>
													<td style='padding-right:15px;'>
														<select style='width:100%;' name='Platform' title='Make sure that if you enter a download link for this platform, the relevent platform is set to Yes above'>
														";
														
														foreach($GAMEPLATFORMSBITS as $temp){
															if($temp['DownloadAllowed']==1 && $GameInfo['PlatformBITS'] & $temp['ID']){
																echo "<option value='".$temp['ID']."'>".$temp['Platform']."</option>";
															}
														}
															
														echo  "
														</select>	
													</td>
													<td style='padding-right:15px;'><b>Direct:</b></td>
													<td style='padding-right:15px;'>
														<select style='width:100%;' name='Direct' title='Does clicking the link start the download (direct) or do they have to visit another webpage (Indirect)'>
															<option value='1' selected='Selected'>Yes</option>
															<option value='0'>No</option>
														</select>	
													</td>
													<td style='padding-right:15px;'><b>Version:</b></td>
													<td style='padding-right:15px;'>
														<input style='width:100%;' class='validate[maxSize[15],custom[downloadnotes]]]' data-prompt-position='bottomLeft' maxlength='15' type='text' name='Ver' value='' />
													</td>
													<td style='padding-right:15px;'><b>FileSize:</b></td>
													<td style='padding-right:15px;'>
														<input style='width:100%;' class='validate[custom[filesize]]' data-prompt-position='bottomLeft' type='text' name='File' value='' title='Size of download in MB' />
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
											
										}	
									
									}else{
										echo "
										<a name='AddDownloadLinks'><h3>No Download Links Allowed</h3></a>
										<div style='width:100%;text-align:center;'>None of the platforms that this game works with need download links, if this is incorrect add more platforms to this game above</div>
										";
									}
									
									
									if($FDBPrivilages["RLinks"] == 2){
										echo "
										<p class='center'><h3>Website Log</h3></p>
										<table width='100%'>
										<colgroup width='150px'></colgroup>
										<colgroup width='40px'></colgroup>
										<colgroup width='100px'></colgroup>
										<colgroup width='70px'></colgroup>
										<colgroup width=*></colgroup>
										<colgroup width='100px'></colgroup>
										<colgroup width='100px'></colgroup>
										<colgroup width='100px'></colgroup>
										
											<tr>
												<td><b>Date</b></td>
												<td><b>ID</b></td>
												<td><b>Operation</b></td>
												<td><b>Link</b></td>
												<td><b>Notes</b></td>
												<td><b>Platform</b></td>
												<td><b>Version</b></td>
												<td><b>Restore</b></td>
												
											</tr>
										";
										$stmt = $Gamedb->prepare("SELECT * FROM downloadsbits_bak WHERE ID=:id ORDER BY `Date` DESC");
										$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
										$stmt->execute();
										$resultsreturned = false;
										while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
											echo "
											<tr>
												<td>".$row['Date']."</td>
												<td>".$row['AutoID']."</td>
												<td>".$row['Operation']."</td>
												<td><a href='".$row['Download']."'>Here</a></td>
												<td>".$row['OS']."</td>
												<td>";
												foreach($GAMEPLATFORMSBITS as $temp){
													if($row['Platform']==$temp['ID']){
														echo $temp['Platform'];
													}
												}
												echo "
												</td>
												<td>".$row['Version']."</td>
												<td><a href='".$DOMAIN."/GameScript.php?Action=RestoreLink&Protocol=POST&AutoID=".$row['AutoID']."&EReturn=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed&Return=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/'>Restore</a></td>
												
											</tr>
											";
											$resultsreturned = true;
										}
										if($resultsreturned == false){
											echo "
											<tr>
												<td colspan='8' style='text-align:center;'>------------------------------------------------Nothing In Log------------------------------------------------</td>
											</tr>
											";
										}
										
										echo "
										</table>
										";
										
									}
									
									echo "<hr />";

									echo "
									<a name='AddReviews'><h3>Game Reviews</h3></a>
									<table width='100%'>
										<colgroup width='80px'></colgroup>
										<colgroup width='*'></colgroup>
									
									";
									
									$stmt = $Gamedb->prepare("SELECT * FROM `reviews` WHERE `ID`=:id");
									$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
									$stmt->execute();									
								
									$ResultsReturned=false;
									while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
									
										
										$ResultsReturned=true;
										echo "
									<tr>
										<td>";

										if($row['Pending']==1){
											echo "Pending:";
										}elseif($row['Flagged']==1){
											echo "Flagged:";
											if($FDBPrivilages['MReviews']==2){
												echo "<br/>By ".$row['FlaggedBy'];
											}
											
										}else{
											echo "Accepted:";
										}
										
										echo "<br/>";
										
										
										if(!($row['Author']==$user->data['username']) && $FDBPrivilages['FReviews'] == 2 && !($row['Flagged']==1 || $row['Pending']==1)){
											echo "(<a href='".$DOMAIN."/GameScript.php?Action=FlagReview&Protocol=POST&Return=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/&EReturn=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed&UniqueID=".$row['UniqueID']."'>Flag</a>) ";
										}
										
										if($FDBPrivilages['MReviews'] == 2){
											if($row['Flagged']==1 || $row['Pending']==1){
												echo "(<a href='".$DOMAIN."/GameScript.php?Action=AcceptReview&Protocol=POST&Return=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/&EReturn=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed&UniqueID=".$row['UniqueID']."'>Accept</a>) ";
											}
										}
										
										if($row['Author']==$user->data['username'] || $FDBPrivilages['DReviews'] == 2){
											echo "(<a href='".$DOMAIN."/GameScript.php?Action=DeleteReview&Protocol=POST&Return=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/&EReturn=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed&UniqueID=".$row['UniqueID']."'>Delete</a>) ";
										}							

										echo "
										</td>
										<td style='border-width:1px;border-style:solid;border-color:gray;'>
											<div style='margin-left:15px;margin-right:15px;margin-bottom:10px;'>
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
															<pre style='width; 100%;font-family: Arial,sans-serif , \"Times New Roman\";font-size:14px;'>
".$row['Review']."</pre>
															<div style='font-size:12px;color:#707070'>";
																echo "	
																Review by ".$row['Author']." at ".$row['Date']."<br/>"; 
																													
																echo "
															</div>									
														</div>
													</td>
												</tr>
												</table>
											
											</div>
										</td>
									</tr>
										";
										
									}
														
									echo "
									</table>
									
									<br/>";
									if($FDBPrivilages['AReviews'] > 0){
										$stmt = $Gamedb->prepare("SELECT * FROM `reviews` WHERE `ID`=:id AND `Author`=:user");
										$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
										$stmt->bindValue(':user', $user->data['username'], PDO::PARAM_STR);
										$stmt->execute();
										if($temp = $stmt->fetch(PDO::FETCH_ASSOC)){		

									echo "

									<h3>Edit Your Review</h3>

									<form id='ReviewForm' action='".$DOMAIN."/GameScript.php' method='post'>
									<input type='hidden' name='Action' value='AddReview'>	
									<input type='hidden' name='Protocol' value='POST'>	
									<input type='hidden' name='Return' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/'>	
									<input type='hidden' name='EReturn' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed'>	
									<table width='100%'>
										<colgroup width='*'></colgroup>
										<colgroup width='*'></colgroup>
									<tr>
										<td class='center'>
											<b>Pros</b><br/>
<textarea style='width:100%;Height:100px;' class='validate[custom[textboxs]]' data-prompt-position='topLeft' name='Pros'>".$temp['Pros']."
</textarea>							
										</td>
										<td class='center'>
											<b>Cons</b><br/>
<textarea style='width:100%;Height:100px;' class='validate[custom[textboxs]]' data-prompt-position='topLeft' name='Cons'>".$temp['Cons']."
</textarea>							
										</td>					
									</tr>
									<tr>
										<td colspan='2' class='center'>
											<b>Overall Opinion</b><br/>
											<textarea style='width:100%;Height:80px;' class='validate[required,custom[textboxs]]' data-prompt-position='bottomLeft' name='Review'>".$temp['Review']."</textarea>							
										</td>
									</tr>
									</table>
									<input type='hidden' name='ID' value='".$GameInfo['ID']."'>
									<div style='margin-left:5px;margin-top:5px;'>
									<b>Rate:</b>
									<select style='width:140px;' name='Rate'>";
									for($i=1;$i<11;$i++){
										echo "<option value='".$i."' ";
										if((int)$i==(int)$temp['Rating']){
											echo "selected='selected'";
										}
										echo ">".$i."/10</option>";	
									}
									echo "
									</select>	
									</div>
									<table width='100%'>
										<colgroup width='*'>
										<colgroup width='180px'>
										<colgroup width='*'>
									
									<tr>
										<td></td>
										<td>
											<input style='width:180px;' type='submit' value='Submit Review' />	
										</td>
										<td></td>
									</tr>
									</table>
									</form>
									";

										}else{

									echo "
								
									<h3>Add a Review</h3>
									<form id='ReviewForm' action='".$DOMAIN."/GameScript.php' method='post'>
									<input type='hidden' name='Action' value='AddReview'>	
									<input type='hidden' name='Protocol' value='POST'>	
									<input type='hidden' name='Return' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/'>	
									<input type='hidden' name='EReturn' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed'>	
									<table width='100%'>
										<colgroup width='*'></colgroup>
										<colgroup width='*'></colgroup>
									<tr>
										<td class='center'>
											<b>Pros</b><br/>
<textarea style='width:100%;Height:100px;' class='validate[custom[textboxs]]' data-prompt-position='topLeft' name='Pros'>
+
+
+
</textarea>							
										</td>
										<td class='center'>
											<b>Cons</b><br/>
<textarea style='width:100%;Height:100px;' class='validate[custom[textboxs]]' data-prompt-position='topLeft' name='Cons'>
-
-
-
</textarea>							
										</td>					
									</tr>
									<tr>
										<td colspan='2' class='center'>
											<b>Overall Opinion</b><br/>
											<textarea style='width:100%;Height:80px;' class='validate[required,custom[textboxs]]' data-prompt-position='bottomLeft'  name='Review'></textarea>							
										</td>
									</tr>
									</table>
									<input type='hidden' name='ID' value='".$GameInfo['ID']."'>
									<div style='margin-left:5px;margin-top:5px;'>
									<b>Rate:</b>
									<select style='width:140px;' name='Rate'>
										<option value='1'>1/10</option>											
										<option value='2'>2/10</option>
										<option value='3'>3/10</option>
										<option value='4'>4/10</option>
										<option value='5'>5/10</option>
										<option value='6'>6/10</option>
										<option value='7'>7/10</option>
										<option value='8'>8/10</option>
										<option value='9'>9/10</option>
										<option value='10'>10/10</option>											
									</select>	
									</div>
									<table width='100%'>
										<colgroup width='*'>
										<colgroup width='180px'>
										<colgroup width='*'>
									
									<tr>
										<td></td>
										<td>
											<input style='width:180px;' type='submit' value='Submit Review' />	
										</td>
										<td></td>
									</tr>
									</table>
									</form>
									
									";						

									

											
										}
									echo "
									<br/>
									";
									}
									
									
									if($FDBPrivilages["RReviews"] == 2){
										echo "
										<p class='center'><h3>Review Log</h3></p>
										<table width='100%'>
										<colgroup width='150px'></colgroup>
										<colgroup width='40px'></colgroup>
										<colgroup width='100px'></colgroup>
										<colgroup width=*></colgroup>
										<colgroup width='120px'></colgroup>
										<colgroup width='100px'></colgroup>
										
											<tr>
												<td><b>Date</b></td>
												<td><b>ID</b></td>
												<td><b>Operation</b></td>
												<td><b>Review</b></td>
												<td><b>Author</b></td>
												<td><b>Restore</b></td>
												
											</tr>
										";
										$stmt = $Gamedb->prepare("SELECT * FROM reviews_bak WHERE ID=:id ORDER BY `Date` DESC");
										$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
										$stmt->execute();
										$resultsreturned = false;
										while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
											echo "
											<tr>
												<td>".$row['Date']."</td>
												<td>".$row['AutoID']."</td>
												<td>".$row['Operation']."</td>
												<td>".substr($row['Review'],0,200)."... </td>
												<td>".$row['Author']."</td>
												<td><a href='".$DOMAIN."/GameScript.php?Action=RestoreReview&Protocol=POST&AutoID=".$row['AutoID']."&EReturn=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed&Return=Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/'>Restore</a></td>
												
											</tr>
											";
											$resultsreturned = true;
										}
										if($resultsreturned == false){
											echo "
											<tr>
												<td colspan='6' style='text-align:center;'>------------------------------------------------Nothing In Log------------------------------------------------</td>
											</tr>
											";
										}
										echo "
										</table>
										";
										
									}
				
																			
									if($FDBPrivilages['DGame'] == 2 || $FDBPrivilages['MGame'] == 2){
										echo "
										<hr/>
										<br/>
										<h3>Admin Tools</h3>
										<table width='100%'>
											<colgroup width='*'></colgroup>
											<colgroup width='210px'></colgroup>
											<colgroup width='210px'></colgroup>
											<colgroup width='*'></colgroup>
										<tr>
											<td></td>
											<td style='text-align:center'>
											";
											if($FDBPrivilages['DGame'] == 2){
											echo "
											<b>Delete Game</b>
											";
											}
											echo "
											</td>
											<td style='text-align:center'>";
											if($FDBPrivilages['MGame'] == 2){
											echo "
											<b>Merge Game</b>
											";
											}
											echo "</td>
											<td></td>
										</tr>
										<tr>
											<td></td>
											<td>
												";
												if($FDBPrivilages['DGame'] == 2){
												echo "
												<form action='".$DOMAIN."/GameScript.php' method='POST'>
													<input type='hidden' name='ID' value='".$GameInfo['ID']."'>										
													<input type='hidden' name='Return' value='Games/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=GameDeleted'>	
													<input type='hidden' name='EReturn' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed'>
													<input type='hidden' name='Protocol' value='POST'>	
													<input type='hidden' name='Action' value='DeleteGame'>	<br/>
													<input style='width:205px;' type='submit' value='Delete Game' />	
													
												</form>
												";
												}
												
												echo "
											</td>
											<td style='text-align:center'>";
												if($FDBPrivilages['MGame'] == 2){
												echo "
												<form action='".$DOMAIN."/GameScript.php' method='POST'>
													<input type='hidden' name='ID' value='".$GameInfo['ID']."'>										
													<input type='hidden' name='Return' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=GameMerged'>	
													<input type='hidden' name='EReturn' value='Edit/".$GameInfo['ID']."/".urlencode($GameInfo['Name'])."/?Error=Failed'>
													<input type='hidden' name='Protocol' value='POST'>	
													<input type='hidden' name='Action' value='MergeGame'>	
													Merge Game ID: <input style='width:50px;' type='text' name='MergeID' value=''><br/>
													<input style='width:205px;' type='submit' value='Merge Game'>	
													
												</form>
												";
												}
												echo "
											</td>
											<td></td>
										</tr>
										</table>
										";
									}									
										
									echo "
									<hr/>
									
								</div>
							</div>
							</td>
							<td></td>
						</tr>
						
						
						</table>";
						}else{
							echo "
							<div class='news'>
							<h1>Error Retrieving Game</h1>
							<p class='center'>
							Oh dear, it seems we couldn't find the game you were looking for. Why not try one of the games below.
							</p>
							</div>
							";
							include("./Includes/GamesSliders.php");
						}
					}else{
						echo "
						<div class='news' >
							<div class='center' style=\"margin:5px;\" >
								You are either not logged in or don't have sufficient privilages to edit games.<br/>
							</div>
						</div>
						
						";
					}

					
							
include("./Includes/Footer.php");
include("./Includes/FooterFunctions.php");
?>