<?php
$PageName = "GetGame.php";
$DeletedGame = false;
include("./Includes/HeaderFunctions.php");

if (!isset($_REQUEST['ID'])){
	header( "Location: ".$DOMAIN."/Games/0/Unknown Game/" );
}

$_CLEANREQUEST = array();
		
foreach($_GET as $key => $value){
	$_CLEANREQUEST[addslashes(htmlspecialchars($key,ENT_QUOTES))] = addslashes(htmlspecialchars($value,ENT_QUOTES));
}


$stmt = $Gamedb->prepare("SELECT * FROM freegames WHERE ID=:id");
$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
$stmt->execute();
$GameInfo = $stmt->fetch(PDO::FETCH_ASSOC);

	if(!($GameInfo)){
		$stmt = $Gamedb->prepare("SELECT * FROM freegames_bak WHERE `ID`=:id AND `Operation`='Delete'");
		$stmt->bindValue(':id', $_CLEANREQUEST["ID"], PDO::PARAM_INT);
		$stmt->execute();
		$GameInfo = $stmt->fetch(PDO::FETCH_ASSOC);	
		$DeletedGame = true;
	}


if($GameInfo){
	
	$MetaDescription = $GameInfo['Name'].", ".$GameInfo['About'];
	$MetaTitle = $GameInfo['Name']." - ".$DOMAINTITLE;
	if (substr($_SERVER['REQUEST_URI'],0,12)=="/GetGame.php"){
		header ('HTTP/1.1 301 Moved Permanently');
		header ("Location: ".$DOMAIN."/Games/".$GameInfo['ID']."/".$GameInfo['Name']."/");
		die();	
	}	
	
	$Genre = "";
	$Graphics = "";
	$Source = "";
	$Studio = "";	
	$Type = "";
	$Release = "";
	$Age = "";
	
	$Mode = "";
	foreach($GAMEMODESBITS as $temp){
		if($temp['ID'] & $GameInfo['ModeBITS']){
			$Mode = $Mode.$temp['Mode'].", ";
		}
	}
	$Mode = substr($Mode,0,-2);
	
	$Genre = "";
	foreach($GAMEGENRESBITS as $temp){
		if($temp['ID'] & $GameInfo['GenreBITS']){
			$Genre = $Genre.$temp['Genre'].", ";
		}
	}
	$Genre = substr($Genre,0,-2);
	
	$Platform = "";
	foreach($GAMEPLATFORMSBITS as $temp){
		if($temp['ID'] & $GameInfo['PlatformBITS']){
			$Platform = $Platform.$temp['Platform'].", ";
		}
	}
	$Platform = substr($Platform,0,-2);	

	foreach($GAMEAGES as $temp){
		if($temp['ID']==$GameInfo['Age']){
			$Age = $temp['Age'];
		}
	}
	
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
	$MetaTitle = $DOMAINTITLE;		
}

$MetaKeywords = "Free,games,database,mac,windows,linux,genres,3D,2D";


include("./Includes/Header.php");

										
					if($GameInfo){

					
						if($GameInfo['Age']!=2 || calculate_age($user->data['user_birthday'])>17 || $FDBPrivilages["V18+Game"]==2){
					

						echo "
						<br/>

							<div class='center' style=\"margin:5px;color: #FF0000;\" >";
										if(isset($_GET["Error"])){
											if($_GET["Error"]=='AR'){
												echo "<div class='center' >
												You have already reviewed this game.
												</div>";
											}
											if($_GET["Error"]=='LinkPending'){
												echo "<div class='center' >
												Link submitted to moderator to check.
												</div>";
											}										
									
											
										}
						echo "
							</div>					
						
						<table width='100%'>
							<colgroup width='20'></colgroup>
							<colgroup width='200'></colgroup>
							<colgroup width='50'></colgroup>
							<colgroup width='*'></colgroup>
							<colgroup width='20'></colgroup>
						<tr>
							<td colspan='5'><br/></td>
						</tr>
						<tr>
							<td></td>
							<td class='AlignTop'>
						
							
							";

							$NoRes = true;
							$b = 1;
							do{
								if($DeletedGame == true){
									$stmt2 = $Gamedb->prepare("SELECT * FROM resources_bak WHERE ID=:id AND Type=:num AND Pending='0' AND Flagged='0' AND Operation='DeleteGame'");
								}else{
									$stmt2 = $Gamedb->prepare("SELECT * FROM resources WHERE ID=:id AND Type=:num AND Pending='0' AND Flagged='0'");
								}
								$stmt2->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
								$stmt2->bindValue(':num', $b, PDO::PARAM_INT);
								$stmt2->execute();	
								if($Resrow = $stmt2->fetch(PDO::FETCH_ASSOC)){
									$Res = $Resrow['ResourcePath'];
									$NoRes = false;
								}
								switch($b){
									case 1: 
										$b = 3;
										break;
									case 3:
										$b = 4;
										break;
									case 4:
										$b = 5;
										break;
									case 5:
										$NoRes = false;
										break;
								}
							}while($NoRes == true);

							if ($Res==null or $Res==""){
								$Res = "NoArtwork/NoShot.png";
							}

							echo "<img alt='A picture of ".$GameInfo['Name']."' src='".artwork_correct($Res)."' style='max-height:400px;max-width:300px' />";
																		
										
										
									
										
								
								echo "
								
								<br/>
								<br/>
								<br/>
								<table width='100%'>
									
									<colgroup width='*'></colgroup>	
									
								<tr>
									
									<td class='center'>";
									if($GameInfo['Trailer']==" " or $GameInfo['Trailer']=="" ){
										echo "No Youtube Trailer Has Been Added<br/>
										<a href='".$DOMAIN."/EditGame.php?ID=".$GameInfo["ID"]."#Trail'>Add Trailer</a>";
									}else{
										echo "
										<div class='video-wrapper'>
											<div class='video-container'>
												<iframe width='800' height='600' src='http://www.youtube.com/embed/".$GameInfo['Trailer']."?wmode=opaque' frameborder='0' allowfullscreen></iframe>
											</div>
										</div>";
									}
									
									echo "</td>
									
								</tr>
								</table>						
								<br/>
								
							</td>
									
							<td>
							</td>
							<td class='AlignTop'>";
								if($DeletedGame == true){
									echo "
									<h1>".$GameInfo['Name']."";
									if($FDBPrivilages["DGame"]==2){
										echo "
										<a href='".$DOMAIN."/GameScript.php?Action=RestoreGame&Protocol=POST&AutoID=".$GameInfo['AutoID']."&ID=".$GameInfo['ID']."&EReturn=Games/".$GameInfo['ID']."/".$GameInfo['Name']."/?Error=Failed&Return=Games/".$GameInfo['ID']."/".$GameInfo['Name']."/'>[Restore Deleted Game]</a>
										";
									}else{
									echo " [Game Binned]";
									}
									echo "
									</h1>
									";

								}else{
									echo "
									<h1>".$GameInfo['Name']." <a href='".$DOMAIN."/Edit/".$GameInfo["ID"]."/".$GameInfo['Name']."/'>[Edit Game]</a></h1>
									";
								}
								
															
								echo "
								
								<p class='GameDesc'>
								".$GameInfo['About']."</p>	
								
								<table width='100%'>
									
									<colgroup width='120px'></colgroup>
									<colgroup width='80px'></colgroup>
									<colgroup width='*'></colgroup>
									<tr>
								
									<td> ";
										
									if(false && $FDBPrivilages['ARating']==0){
										echo "<div class=\"RatingDiv\" data-average=\"".round($GameInfo['Rating'],1)."\" data-id=\"1\" title='You must be logged in to rate games'>";
									}else{
										echo "<div class=\"RatingDiv\" data-average=\"".round($GameInfo['Rating'],1)."\" data-id=\"1\">";
									}
										
									echo "
										<noscript>
										<div style='font-size:20px;text-align:center'>".$GameInfo['Rating']."/10 </div>
										</noscript>
										</div>

									</td>
									<td>
									(".$GameInfo['RateNum']." Ratings)
									</td>
									<td>
									<noscript>
										<div class='center'>";
										
										if(false && $FDBPrivilages['ARating']==0){
											echo "You must be logged in to rate games";
										}else{
										
											echo "
											<form method='post' action='".$DOMAIN."/GameScript.php'>
												<input type='hidden' name='ID' value='".$GameInfo['ID']."'>										
												<input type='hidden' name='Action' value='RateGame'>		
												<input type='hidden' name='Protocol' value='POST'>	
												<input type='hidden' name='Return' value='Games/".$GameInfo['ID']."/".$GameInfo['Name']."/'>	
												<input type='hidden' name='EReturn' value='Games/".$GameInfo['ID']."/".$GameInfo['Name']."/?Error=Failed'>
												
												<select name='Rate'>
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
												
												<input style='width:180px;' type='submit' value='Rate' />	
											</form>";
										}
										echo "	
											
										</div>
									</noscript>


									<div style='width:220px;margin-left:auto;margin-right:0;'>
											<!-- AddThis Button BEGIN -->
											<div class=\"addthis_toolbox addthis_default_style addthis_32x32_style\">
											<a class=\"addthis_button_preferred_1\"></a>
											<a class=\"addthis_button_preferred_2\"></a>
											<a class=\"addthis_button_preferred_3\"></a>
											<a class=\"addthis_button_preferred_4\"></a>
											<a class=\"addthis_button_preferred_5\"></a>							
											<a class=\"addthis_button_compact\"></a>
											</div>
											<script type=\"text/javascript\" src=\"http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50d0bdee4ad2bdab\"></script>
											<!-- AddThis Button END -->								
									</div>								
									</td>
									</tr>
								</table>
								
															
								
								<hr/>
								<table width='100%'>
									<colgroup width='100px'></colgroup>
									<colgroup width='*'></colgroup>
									<colgroup width='100px'></colgroup>
									<colgroup width='*'></colgroup>							
								<tr>
									<td>
										<b>Platforms:</b>
									</td>
									<td colspan='3'>".$Platform."</td>

								</tr>
								<tr>
									<td colspan='4'><div style='width:15px;'></div></td>
								</tr>
								
								<tr>
									<td>
										<b>Modes:</b>
									</td>
									<td colspan='3'>".$Mode."</td>
								</tr>
								<tr>
									<td colspan='4'><div style='width:15px;'></div></td>
								</tr>
								
								<tr>
									<td>
										<b>Genres:</b>
									</td>
									<td colspan='3'>".$Genre."</td>

								</tr>
								<tr>
									<td colspan='4'><hr/></td>
								</tr>

															
								
								<tr>
									<td>
										<b>Studio:</b>
									</td>
									<td>".$Studio."</td>
									<td>
										<b>Graphics:</b>
									</td>
									<td>".$Graphics."</td>

								</tr>
								<tr>
									<td>
										<b>Type:</b>
									</td>		
									<td>".$Type."</td>	
									<td>
										<b>Source:</b>
									</td>
									<td>".$Source."</td>
								</tr>
								<tr>
									<td>
										<b>Release:</b>
									</td>
									<td>".$Release."</td>
									<td>
										<b>Age:</b>
									</td>
									<td>".$Age."</td>
								</tr>
								</table>					
								
								<hr/>
								<div id=\"accordion\">
								<h3>Website</h3>
								<div>
									
											<p>
												<table width='100%'>
													<colgroup width='80px'></colgroup>
													<colgroup width='*'></colgroup>
													<colgroup width='60px'></colgroup>
													<colgroup width='100px'></colgroup>
												
										";
										if($DeletedGame == true){
											$stmt = $Gamedb->prepare("SELECT * FROM websites_bak WHERE ID=:id AND Operation='DeleteGame'");
										}else{
											$stmt = $Gamedb->prepare("SELECT * FROM websites WHERE ID=:id");
										}
										$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
										$stmt->execute();	
										while($Webrow = $stmt->fetch(PDO::FETCH_ASSOC)){
											if($Webrow['Pending']==0){
												echo "
												<tr>
												<td>
													<b>Domain:</b>
												</td>
												<td>
													".GetDomain($Webrow['Website'])."
												</td>
												<td>
													<b>Link:</b>
												</td>
												<td>
													<a target='_blank' href='".$Webrow['Website']."'>Click Here</a>
												</td>
												</tr>
												<tr>
												<td>
													<b>Notes:</b>
												</td>
												<td colspan=3>
													".$Webrow['Notes']."
												</td>
												</tr>
												<tr>
												<td colspan='4'>
													<br/>
												</td>
												</tr>
												";
											}
										}
										
										echo "
												</tr>
												</table>
											</p>
								</div>
								
								";
								foreach($GAMEPLATFORMSBITS as $temp){
									if($temp['DownloadAllowed']==1 && $GameInfo['PlatformBITS'] & $temp['ID'] ){
										if($DeletedGame == true){
											$stmt = $Gamedb->prepare("SELECT * FROM downloadsbits_bak WHERE ID=:id AND Platform & ".$temp['ID']." AND Pending=0 AND Flagged=0 AND Operation='DeleteGame'");
										}else{
											$stmt = $Gamedb->prepare("SELECT * FROM downloadsbits WHERE ID=:id AND Platform & ".$temp['ID']." AND Pending=0 AND Flagged=0");
										}
										$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
										$stmt->execute();
										if($stmt->rowCount()){
										echo "
										<h3>".$temp['Platform']." Downloads</h3>
										<div>";
										

										while($Downrow = $stmt->fetch(PDO::FETCH_ASSOC)){
											
											echo "
												
												<p>
													<table width='100%'>
														<colgroup width='100px'></colgroup>
														<colgroup width='230px'></colgroup>
														<colgroup width='65px'></colgroup>
														<colgroup width='110px'></colgroup>
													<tr>
														<td><b>Domain:</b></td>
														<td>".GetDomain($Downrow['Download'])."</td>
														<td><b>Download:</b></td>
														<td style='text-align:left;'>";
														
															echo "<a target='_blank' href='".link_correct($Downrow['Download'])."' rel=\"nofollow\">Click Here</a>";
														
														
														echo "
														</td>
													</tr>
													<tr>
														<td><b>Version:</b></td>
														<td style='text-align:left;'>".$Downrow['Version']."</td>
														
														
														
														<td><b>FileSize:</b></td>
														<td style='text-align:left;'>".round($Downrow['FileSize'],2)."MB</td>
													</tr>
													<tr>
														<td><b>Notes:</b></td>
														<td>".$Downrow['OS']."</td>
														<td><b>Link:</b></td>
														<td>";
														if($Downrow['Direct']==1){
															echo "Direct";
														}else{
															echo "Indirect";
														}
														echo "
														</td>
														
													</tr>	

													</table>
													
												</p>
												<br/>
											";

											
										}
										echo "</div>";
										}
									}
								}	
									
									
								echo "
								
								</div>
								<br />
														
							</td>
							<td></td>
						</tr>
						
						</table>
						<br/>
						<noscript>
						<hr/>
						<h3>Artwork</h3>
						</noscript>

						<div id=\"accordion3\">
						<ul id='accordion3_tablist'>
							<li><a href=\"#reviews\">Game Reviews</a></li>
							<li><a href=\"#Cover\">Cover Art</a></li>
							<li><a href=\"#fanart\">Fan Art</a></li>
							<li><a href=\"#screenshots\">Screen Shots</a></li>
							<li><a href=\"#banner\">Banner Art</a></li>
							<li><a href=\"#problems\">Problems or Error with Entry?</a></li>
							
							
						</ul>
							<div id='Cover'>
								<div style='width:300px;margin-left:auto;margin-right:auto;'>
								<div id=\"nofrontbackslide\" class=\"nosvwp\">
										<ul>";
										if($DeletedGame == true){
											$stmt = $Gamedb->prepare("SELECT * FROM resources_bak WHERE ID=:id AND Type='2' AND Pending='0' AND Flagged='0' AND Operation='DeleteGame'");
										}else{
											$stmt = $Gamedb->prepare("SELECT * FROM resources WHERE ID=:id AND Type='2' AND Pending='0' AND Flagged='0'");
										}
										$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
										$stmt->execute();	
																	
										$ResBReturned = false;
										$a = 0;
										while($Resrow = $stmt->fetch(PDO::FETCH_ASSOC))
										{
											$ResBReturned = true;
											echo "<li><img alt='A picture of ".$GameInfo['Name']."' src='".artwork_correct($Resrow['ResourcePath'])."' style='max-height:400px;max-width:300px' /></li>
											";
											break;
										}
										if($ResBReturned == false){
											echo "<li><img alt='A picture of ".$GameInfo['Name']."' src='".artwork_correct("NoArtwork/NoBCover.png")."' style='max-height:400px;max-width:300px' /></li>
											";
										}	

										if($DeletedGame == true){
											$stmt = $Gamedb->prepare("SELECT * FROM resources_bak WHERE ID=:id AND Type='1' AND Pending='0' AND Flagged='0' AND Operation='DeleteGame'");
										}else{
											$stmt = $Gamedb->prepare("SELECT * FROM resources WHERE ID=:id AND Type='1' AND Pending='0' AND Flagged='0'");
										}
										$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
										$stmt->execute();									
										
										$ResFReturned = false;
										$a = 0;
										while($Resrow = $stmt->fetch(PDO::FETCH_ASSOC))
										{
											$ResFReturned = true;
											echo "<li><img alt='A picture of ".$GameInfo['Name']."' src='".artwork_correct($Resrow['ResourcePath'])."' style='max-height:400px;max-width:300px' /></li>
											";
											break;
										}
										if($ResFReturned == false){
											echo "<li><img alt='A picture of ".$GameInfo['Name']."' src='".artwork_correct("NoArtwork/NoFCover.png")."' style='max-height:400px;max-width:300px' /></li>
											";
										}										
										
										
									echo"
										</ul>
								</div>";
								if($ResFReturned == false && $ResBReturned == true){
									echo "<p class='center'>
									No Front Cover Art in database<br/>
									<a href='".$DOMAIN."/EditGame.php?ID=".$GameInfo['ID']."#Art'>Why not add some?</a>
									</p>";
								}elseif($ResFReturned == true && $ResBReturned == false){
									echo "<p class='center'>
									No Back Cover Art in database<br/>
									<a href='".$DOMAIN."/EditGame.php?ID=".$GameInfo['ID']."#Art'>Why not add some?</a>
									</p>";
								}elseif($ResFReturned == false && $ResBReturned == false){
									echo "<p class='center'>
									No Cover Art in database<br/>
									<a href='".$DOMAIN."/EditGame.php?ID=".$GameInfo['ID']."#Art'>Why not add some?</a>
									</p>";
								}
								echo "	
								</div>
							</div>						
							
							<div id='fanart'>
								<div style='width:400px;margin-left:auto;margin-right:auto;'>
								<div id=\"nofanartslide\" class=\"nosvwp\">
										<ul>";
										if($DeletedGame == true){
											$stmt = $Gamedb->prepare("SELECT * FROM resources_bak WHERE ID=:id AND Type='3' AND Pending='0' AND Flagged='0' AND Operation='DeleteGame'");
										}else{
											$stmt = $Gamedb->prepare("SELECT * FROM resources WHERE ID=:id AND Type='3' AND Pending='0' AND Flagged='0'");
										}
										$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
										$stmt->execute();									
										
										$ResReturned = false;
										$a = 0;
										while($Resrow = $stmt->fetch(PDO::FETCH_ASSOC))
										{
											$ResReturned = true;
											echo "<li><img alt='A picture of ".$GameInfo['Name']."' src='".artwork_correct($Resrow['ResourcePath'])."' width='400px' /></li>
											";
											$a = $a +1;
										}
										if($ResReturned == false){
											echo "<li><img alt='A picture of ".$GameInfo['Name']."' src='".artwork_correct("NoArtwork/NoBack.png")."' width='400px' /></li>
											";
										}		
										
										
									echo"
										</ul>
								</div>";
								if($ResReturned == false){
									echo "<p class='center'>
									No Fan Art in database<br/>
									<a href='".$DOMAIN."/EditGame.php?ID=".$GameInfo['ID']."#Art'>Why not add some?</a>
									</p>";
								}
								echo "	
								</div>
							</div>
												
							<div id='screenshots'>
								<div style='width:400px;margin-left:auto;margin-right:auto;'>
								<div id=\"noscreenshotsslide\" class=\"nosvwp\">
										<ul>";
										
										if($DeletedGame == true){
											$stmt = $Gamedb->prepare("SELECT * FROM resources_bak WHERE ID=:id AND Type='4' AND Pending='0' AND Flagged='0' AND Operation='DeleteGame'");
										}else{
											$stmt = $Gamedb->prepare("SELECT * FROM resources WHERE ID=:id AND Type='4' AND Pending='0' AND Flagged='0'");
										}
										$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
										$stmt->execute();
										
										$ResReturned = false;
										$a = 0;
										while($Resrow = $stmt->fetch(PDO::FETCH_ASSOC))
										{
											$ResReturned = true;
											echo "<li><img alt='A picture of ".$GameInfo['Name']."' src='".artwork_correct($Resrow['ResourcePath'])."' width='400px' /></li>
											";
											$a = $a +1;
										}
										if($ResReturned == false){
											echo "<li><img alt='A picture of ".$GameInfo['Name']."' src='".artwork_correct("NoArtwork/NoShot.png")."' width='400px' /></li>
											";
										}		
										
										
									echo"
										</ul>
								</div>";
								if($ResReturned == false){
									echo "<p class='center'>
									No Screen Shots in database<br/>
									<a href='".$DOMAIN."/EditGame.php?ID=".$GameInfo['ID']."#Art'>Why not add some?</a>
									</p>";
								}
								echo "	
								</div>
							</div>
													
							<div id='banner'>
								<div style='width:800px;margin-left:auto;margin-right:auto;'>
								<div id=\"nobannerslide\" class=\"nosvwp\">
										<ul>";
										
										if($DeletedGame == true){
											$stmt = $Gamedb->prepare("SELECT * FROM resources_bak WHERE ID=:id AND Type='5' AND Pending='0' AND Flagged='0' AND Operation='DeleteGame'");
										}else{
											$stmt = $Gamedb->prepare("SELECT * FROM resources WHERE ID=:id AND Type='5' AND Pending='0' AND Flagged='0'");
										}
										$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
										$stmt->execute();									
										
										
										$ResReturned = false;
										$a = 0;
										while($Resrow = $stmt->fetch(PDO::FETCH_ASSOC))
										{
											$ResReturned = true;
											echo "<li><img alt='A picture of ".$GameInfo['Name']."' src='".artwork_correct($Resrow['ResourcePath'])."' width='800px' /></li>
											";
											$a = $a +1;
										}
										if($ResReturned == false){
											echo "<li><img alt='A picture of ".$GameInfo['Name']."' src='".artwork_correct("NoArtwork/NoBanner.png")."' width='800px' /></li>
											";
										}		
										
										
									echo"
										</ul>
								</div>";
								if($ResReturned == false){
									echo "<p class='center'>
									No Banner Art in database<br/>
									<a href='".$DOMAIN."/EditGame.php?ID=".$GameInfo['ID']."#Art'>Why not add some?</a>
									</p>";
								}
								echo "	
								</div>
							</div>
							
							<script type='text/javascript'>
								document.getElementById('nofrontbackslide').id = 'frontbackslide';
								document.getElementById('frontbackslide').className = 'svwp';
								
								document.getElementById('nofanartslide').id = 'fanartslide';
								document.getElementById('fanartslide').className = 'svwp';
								
								document.getElementById('noscreenshotsslide').id = 'screenshotsslide';
								document.getElementById('screenshotsslide').className = 'svwp';
								
								document.getElementById('nobannerslide').id = 'bannerslide';
								document.getElementById('bannerslide').className = 'svwp';
								
								document.getElementById('accordion3_tablist').style.visibility = 'visible';
							</script>							
							
							<div id='reviews'>
							<noscript>
							<hr />
							<h3>Game Reviews</h3>
							</noscript>
							";
						
						
							
							
						if($DeletedGame == true){
							$stmt = $Gamedb->prepare("SELECT * FROM `reviews_bak` WHERE `ID`=:id AND Pending='0' AND Flagged='0' AND Operation='DeleteGame'");
						}else{	
							$stmt = $Gamedb->prepare("SELECT * FROM `reviews` WHERE `ID`=:id AND Pending='0' AND Flagged='0'");
						}
						$stmt->bindValue(':id', $GameInfo['ID'], PDO::PARAM_INT);
						$stmt->execute();									
					
						$ResultsReturned=false;
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
						
							
							$ResultsReturned=true;
							echo "
							<div class='news'>
								<div style='margin-left:15px;margin-right:15px;margin-bottom:10px;'>
									<table>
										<colgroup width='450px'></colgroup>
										<colgroup width='25px'></colgroup>
										<colgroup width='450px'></colgroup>
									<tr>
										<td id='BorderT1' class='AlignTop'>
											<div style='margin-left:20px;margin-right:20px;'>
											<b>Pros</b><br/>
											<div style='width; 450px;font-family: Arial,sans-serif , \"Times New Roman\";font-size:14px;'>".$row['Pros']."</div>
											</div>
										</td>
										<td></td>
										<td id='BorderT1' class='AlignTop'>
											<div style='margin-left:20px;margin-right:20px;'>
											<b>Cons</b><br/>
											<div style='width; 450px;font-family: Arial,sans-serif , \"Times New Roman\";font-size:14px;'>".$row['Cons']."</div>
											</div>
										</td>
									</tr>
									<tr>
										<td class='AlignTop' colspan='3'>
											<div style='margin-left:20px;margin-right:20px;margin-top:5px'>
												<b style='font-size:15px'>".$row['Rating']."/10</b>
												<div style='width; 100%;font-family: Arial,sans-serif , \"Times New Roman\";font-size:14px;'>".$row['Review']."</div>
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
							</div>
							
							
							
							";
							
						}
						if($ResultsReturned==false){
						echo "
						<div class='news'>
							<div style='margin-left:15px;margin-right:15px;margin-bottom:10px;margin-top:10px;text-align:center;'>
								No reviews found for this game<br/>
								
							</div>
						</div>
								
								";
						}
						
						echo "
						
						<div class='news'>
							<div style='margin-left:15px;margin-right:15px;margin-bottom:10px;margin-top:10px;text-align:center;'>
								Why not add your own review? <a href='".$DOMAIN."/Edit/".$GameInfo['ID']."/".$GameInfo['Name']."/#AddReviews'>Click Here</a><br/>
								
							</div>
						</div>
						
						";
							
						
						
						echo "
						<br/>
				
												
							
							</div>
						<div id='problems'>
							<noscript>
							<hr />
							<h3>Problems with Game Entry?</h3>
							</noscript>
							<div class='news'>
								<div style='margin-left:15px;margin-right:15px;margin-bottom:10px;margin-top:10px'>
									<div style='text-align:center'><b>Is there an error or problem in the game entry?</b></div><br/>
									Is any text detail wrong, excluding the name?<br/>
									Is the trailer wrong?<br/> 
									<b>To correct the above:</b> Please login and goto this games 'Edit Page' (Found by clicking on the link next to the games name) and use the form to correct the details<br/><br/>
									
									Is there a poor quality art work?<br/> 
									Is there a broken link or broken website link?<br/> 
									Is there a poor quality review or a review that is clearly spam?<br/> 
									<b>To correct the above:</b> Please login and goto this games 'Edit Page' (Found by clicking on the link next to the games name) and use the flag hyperlinks next to the specific resource<br/><br/>
									
									Is the game entry a duplicate of another game entry?<br/> 
									Is the games name wrong?<br/>
									<b>To correct the above:</b> Please login and goto the forum and post a new thread with the problem<br/><br/>
								</div>
							</div>
						</div>
						
						</div>
						
						
						
						";
						}else{
							echo "
							<div class='news'>
							<h1>18+ Content</h1>
							<p class='center'>
							You are either not logged in or are not old enough to view this game.<br/>
							(Note: You must set your birthday in your forum profile)
							</p>
							</div>
							";
						
						}

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

include("./Includes/Footer.php");
include("./Includes/FooterFunctions.php");

?>
