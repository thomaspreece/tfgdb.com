<?php
$PageName = "Games.php";
include("./Includes/HeaderFunctions.php");

		$SearchLine = "WHERE";
		$TagLine = "";
		
		if(isset($_GET['ClearFilters'])){
			header( "Location: Games.php");
		}
		
		$_CLEANREQUEST = array();
		
		foreach($_GET as $key => $value){
			$_CLEANREQUEST[addslashes(htmlspecialchars($key,ENT_QUOTES))] = addslashes(htmlspecialchars($value,ENT_QUOTES));
		}
	
		if (isset($_CLEANREQUEST["IPP"])){
			$tempIPP = $_CLEANREQUEST["IPP"];
			if ($tempIPP < 1){
					$tempIPP = 1;
			}
			if ($tempIPP > 100){
					$tempIPP = 100;
			}							
			$IPP = "IPP=".$tempIPP."&";
			$IPP2 = $tempIPP;
		}else{
			$IPP = "IPP=".(20)."&";
			$IPP2 = 20;
		}
		
		$ModeArray = array();
		$GenreArray = array();
		$PlatformArray = array();
		foreach($_CLEANREQUEST as $key => $value){
			if($value=='on'){
				if(substr($key,0,4)=='Mode'){
					$ModeArray[] = substr($key,4);
				}elseif(substr($key,0,3)=='Gen'){
					$GenreArray[] = substr($key,3);
				}elseif(substr($key,0,4)=='Plat'){
					$PlatformArray[] = substr($key,4);
				}				
			}
		}
		
		$ModeLine = "";
		foreach($ModeArray as $temp){
			$ModeLine = $ModeLine." ModeBITS & ".$temp." OR";
		}
		if($ModeLine!=""){
			$SearchLine = $SearchLine."(".substr($ModeLine,0,-3).") AND";
		}
		
		$GenreLine = "";
		foreach($GenreArray as $temp){
			$GenreLine = $GenreLine." GenreBITS & ".$temp." OR";
		}
		if($GenreLine!=""){
			$SearchLine = $SearchLine."(".substr($GenreLine,0,-3).") AND";
		}		
		
		$PlatformLine = "";
		foreach($PlatformArray as $temp){
			$PlatformLine = $PlatformLine." PlatformBITS & ".$temp." OR";
		}
		if($PlatformLine!=""){
			$SearchLine = $SearchLine."(".substr($PlatformLine,0,-3).") AND";
		}			
		
		
		if (isset($_CLEANREQUEST["Release"]) && $_CLEANREQUEST["Release"] != ""){
			$Release = "Release=".$_CLEANREQUEST["Release"]."&";
			$Release2 = $_CLEANREQUEST["Release"];
			
			foreach($GAMERELEASE as $temp){
				if($temp['ID']==$_CLEANREQUEST["Release"]){
					$TagLine = $TagLine."Release Type ".$temp['Release'].", ";
					$SearchLine = $SearchLine." `Release`='".$_CLEANREQUEST["Release"]."' AND";
				}
			}
		}else{
			$Release = "";
			$Release2 = "";
		}						
		
		if (isset($_CLEANREQUEST["Letter"]) && $_CLEANREQUEST["Letter"] != ""){
			$LetterText = "Letter=".$_CLEANREQUEST["Letter"]."&";
			$LetterText2 = $_CLEANREQUEST["Letter"];
			$_CLEANREQUEST['Search'] = "";
			$SearchLine = $SearchLine." Name LIKE '".$_CLEANREQUEST["Letter"]."%' AND";
		}else{
			$LetterText = "";
			$LetterText2 = "";						
			if (isset($_CLEANREQUEST["Search"])  && $_CLEANREQUEST["Search"] != ""){
				if(strlen($_CLEANREQUEST["Search"])>1){
					$SearchText = "Search=".$_CLEANREQUEST["Search"]."&";
					$SearchText2 = $_CLEANREQUEST["Search"];
					$SearchText3 = false;

					$SearchLine = $SearchLine." (Name LIKE '%".$_CLEANREQUEST["Search"]."%' OR About LIKE '%".$_CLEANREQUEST["Search"]."%') AND";
				}else{
					$SearchText = "";
					$SearchText2 = "";								
					$SearchText3 = true;
				}
			}else{
				$SearchText = "";
				$SearchText2 = "";
				$SearchText3 = false;
			}
		}
		
		if (isset($_CLEANREQUEST["Graphics"])  && $_CLEANREQUEST["Graphics"] != ""){
			$Graphics = "Graphics=".$_CLEANREQUEST["Graphics"]."&";
			$Graphics2 = $_CLEANREQUEST["Graphics"];
			
			foreach($GAMEGRAPHICS as $temp){
				if($temp['ID']==$_CLEANREQUEST["Graphics"]){
					$TagLine = $TagLine.$temp['Graphics']." Graphics, ";
					$SearchLine = $SearchLine." Graphics='".$_CLEANREQUEST["Graphics"]."' AND";
				}
			}			
		}else{
			$Graphics = "";
			$Graphics2 = "";
		}

		if (isset($_CLEANREQUEST["Type"])  && $_CLEANREQUEST["Type"] != ""){
			
			$Type = "Type=".$_CLEANREQUEST["Type"]."&";
			$Type2 = $_CLEANREQUEST["Type"];
			
			foreach($GAMETYPES as $temp){
				if($temp['ID']==$_CLEANREQUEST["Type"]){
					$TagLine = $TagLine.$temp['Type'].", ";
					$SearchLine = $SearchLine." Type='".$_CLEANREQUEST["Type"]."' AND";
				}
			}				
			
		}else{
			$Type = "";
			$Type2 = "";
		}
		
		if (isset($_CLEANREQUEST["Source"]) && $_CLEANREQUEST["Source"] != ""){
			
			$Source = "Source=".$_CLEANREQUEST["Source"]."&";
			$Source2 = $_CLEANREQUEST["Source"];
			
			foreach($GAMESOURCES as $temp){
				if($temp['ID']==$_CLEANREQUEST["Source"]){
					$TagLine = $TagLine.$temp['Source']." Source, ";
					$SearchLine = $SearchLine." Source='".$_CLEANREQUEST["Source"]."' AND";
				}
			}			

		}else{
			$Source = "";
			$Source2 = "";
		}
		
		
		
		if (isset($_CLEANREQUEST["Studio"]) && $_CLEANREQUEST["Studio"] != ""){
			
			$Studio = "Studio=".$_CLEANREQUEST["Studio"]."&";
			$Studio2 = $_CLEANREQUEST["Studio"];
			
			foreach($GAMESTUDIOS as $temp){
				if($temp['ID']==$_CLEANREQUEST["Studio"]){
					$TagLine = $TagLine.$temp['Studio'].", ";
					$SearchLine = $SearchLine." Studio='".$_CLEANREQUEST["Studio"]."' AND";
				}
			}						
			
		}else{
			$Studio = "";
			$Studio2 = "";
		}	

									

		if($SearchLine == "WHERE"){
			$SearchLine = "WHERE `QuedTodaysGame`=0";
		}else{
			$SearchLine = $SearchLine." `QuedTodaysGame`=0";
		}
		
		
		$stmt = $Gamedb->prepare("SELECT * FROM freegames ".$SearchLine);
		$stmt->execute();

		$numrows = $stmt->rowCount();
		//$numrows = mysql_num_rows($Search);
		$MaxPNum = ceil($numrows / $IPP2);
		if (isset($_CLEANREQUEST["Page"])){
			$Page = $_CLEANREQUEST["Page"];
		}else{
			$Page = 1;
		}
		if($Page>$MaxPNum){
			$Page=$MaxPNum;
		}
		if($Page<1){
			$Page=1;
		}
		
		if (isset($_CLEANREQUEST["Sort"])){

			
			if($_CLEANREQUEST["Sort"]==1){
				$SearchLine = $SearchLine." ORDER BY Rating, RateNum ";
				$Sort = "Sort=".$_CLEANREQUEST["Sort"]."&";
				$Sort2 = $_CLEANREQUEST["Sort"];								
			}elseif($_CLEANREQUEST["Sort"]==2){
				$SearchLine = $SearchLine." ORDER BY Rating DESC, RateNum DESC ";
				$Sort = "Sort=".$_CLEANREQUEST["Sort"]."&";
				$Sort2 = $_CLEANREQUEST["Sort"];	
			}elseif($_CLEANREQUEST["Sort"]==3){
				$SearchLine = $SearchLine." ORDER BY ID DESC ";
				$Sort = "Sort=".$_CLEANREQUEST["Sort"]."&";
				$Sort2 = $_CLEANREQUEST["Sort"];									
			}else{
				$SearchLine = $SearchLine." ORDER BY Name ";
				$Sort="";
				$Sort2="";						
			}
		}else{
			$Sort="";
			$Sort2="";
			$SearchLine = $SearchLine." ORDER BY Name ";
		}
		
		//$SearchLine = $SearchLine."LIMIT ".(($Page-1)*$IPP2).",".$IPP2;
		
		//$Search=mysql_query("SELECT * FROM freegames ".$SearchLine."LIMIT ".(($Page-1)*$IPP2).",".$IPP2."");
		if ($TagLine==""){
			$TagLine = "All Games, ";
		}
$MetaDescription = "View our database of free games, browsing by ".substr($TagLine,0,-2);
$MetaKeywords = "Free,games,open,source,closed,commercial,database,mac,windows,linux,genres,3D,2D";
$MetaTitle = substr($TagLine,0,-2)." - ".$DOMAINTITLE;

include("./Includes/Header.php");


echo "
	<script type='text/javascript'>
	
    $(function() {
        $( \"#accordion\" ).accordion({
		active: false,
		collapsible: true
		});
    });
	
    </script>	
";		
					
					
							echo "
							
							<div style=\"margin:5px;color: #FF0000;\">
								<div class='center'>";
								if($SearchText3 == true){
									echo "
									<div class='news'>
									Search must be longer than 3 characters
									</div>";
								}
							echo "
								</div>
							</div>
							";
							echo "
							<table width='100%'>
								<colgroup width='*'></colgroup>
							<tr>
								
								<td class='AlignTop'>
								<div id=\"accordion\">
								<h3>Advanced Filters</h3>
								<div>
								<div class='news'>
									<div style=\"margin:5px;\">
										
										<form action='Games.php' method='GET'>
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
													<b>Search: </b>
												</td>
												<td style='padding-right:15px;' colspan='3'>
													<input style='width:100%;' type=\"text\" name=\"Search\" size=\"10\" value='".$SearchText2."' />
												</td>

		

												<td>
													<b>Letter:</b>
												</td>
												<td style='padding-right:10px;'>
													<select style='width:100%;' name='Letter'>
														<option value=''>All</option>
														<option value='A'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='A'){
																echo " selected='Selected'";
															}
														echo "
														>A</option>
														<option value='B'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='B'){
																echo " selected='Selected'";
															}
														echo "
														>B</option>
														<option value='C'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='C'){
																echo " selected='Selected'";
															}
														echo "
														>C</option>
														<option value='D'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='D'){
																echo " selected='Selected'";
															}
														echo "
														>D</option>
														<option value='E'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='E'){
																echo " selected='Selected'";
															}
														echo "
														>E</option>
														<option value='F'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='F'){
																echo " selected='Selected'";
															}
														echo "
														>F</option>
														<option value='G'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='G'){
																echo " selected='Selected'";
															}
														echo "
														>G</option>
														<option value='H'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='H'){
																echo " selected='Selected'";
															}
														echo "
														>H</option>
														<option value='I'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='I'){
																echo " selected='Selected'";
															}
														echo "
														>I</option>
														<option value='J'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='J'){
																echo " selected='Selected'";
															}
														echo "
														>J</option>
														<option value='K'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='K'){
																echo " selected='Selected'";
															}
														echo "
														>K</option>
														<option value='L'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='L'){
																echo " selected='Selected'";
															}
														echo "
														>L</option>
														<option value='M'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='M'){
																echo " selected='Selected'";
															}
														echo "
														>M</option>														
														<option value='N'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='N'){
																echo " selected='Selected'";
															}
														echo "
														>N</option>														
														<option value='O'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='O'){
																echo " selected='Selected'";
															}
														echo "
														>O</option>														
														<option value='P'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='P'){
																echo " selected='Selected'";
															}
														echo "
														>P</option>														
														<option value='Q'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='Q'){
																echo " selected='Selected'";
															}
														echo "
														>Q</option>														
														<option value='R'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='R'){
																echo " selected='Selected'";
															}
														echo "
														>R</option>														
														<option value='S'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='S'){
																echo " selected='Selected'";
															}
														echo "
														>S</option>														
														<option value='T'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='T'){
																echo " selected='Selected'";
															}
														echo "
														>T</option>														
														<option value='U'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='U'){
																echo " selected='Selected'";
															}
														echo "
														>U</option>														
														<option value='V'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='V'){
																echo " selected='Selected'";
															}
														echo "
														>V</option>														
														<option value='W'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='W'){
																echo " selected='Selected'";
															}
														echo "
														>W</option>														
														<option value='Y'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='Y'){
																echo " selected='Selected'";
															}
														echo "
														>Y</option>														
														<option value='X'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='X'){
																echo " selected='Selected'";
															}
														echo "
														>X</option>														
														<option value='Z'";
															if(isset($_CLEANREQUEST['Letter']) && $_CLEANREQUEST['Letter']=='Z'){
																echo " selected='Selected'";
															}
														echo "
														>Z</option>															
													</select>
												</td>
												
												<td>
													<b>Sort By:</b>
												</td>
												<td style='padding-right:10px;'>
													<select style='width:100%;' name='Sort'>
														<option value='0'";
														if($Sort2==""){echo " selected='Selected'";}
														echo ">Name</option>
														<option value='1'";
														if($Sort2==1){echo " selected='Selected'";}
														echo ">Rating (Worst First)</option>
														<option value='2'";
														if($Sort2==2){echo " selected='Selected'";}
														echo ">Rating (Best First)</option>
														<option value='3'";
														if($Sort2==3){echo " selected='Selected'";}
														echo ">Recently Added</option>
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
															<input type=checkbox name='Gen".$temp['ID']."'";
															if(isset($_CLEANREQUEST['Gen'.$temp['ID']])){
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
													<b>Studio: </b>
												</td>
												<td style='padding-right:10px;'>													
													<select style='width:100%;' name='Studio'>
													<option value=''";
													if($Studio2 == ""){
														echo " selected='Selected'";
													}
													echo ">All</option>";
												

													foreach($GAMESTUDIOS as $temp){
														echo "<option value='".$temp['ID']."'";
														if($temp['ID']==$Studio2){
															echo " selected='Selected'";
														}
														echo ">".$temp['Studio']."</option>
														";
													}
													
													/*
													$StudioSearch=mysql_query("SELECT * FROM studio");
												
													while($Studiorow = mysql_fetch_array($StudioSearch)){	
														echo "<option value='".$Studiorow['ID']."'";
														if($Studiorow['ID']==$Studio2){
															echo " selected='Selected'";
														}
														echo ">".$Studiorow['Studio']."</option>
														";
													}											
														
													*/	
													echo "</select>
												</td>
				

												<td>
													<b>Type: </b>
												</td>
												<td style='padding-right:10px;'>													
													<select style='width:100%;' name='Type'>
													<option value=''";
													if($Type2 == ""){
														echo " selected='Selected'";
													}
													echo ">All</option>";
													
													foreach($GAMETYPES as $temp){
														echo "<option value='".$temp['ID']."'";
														if($temp['ID']==$Type2){
															echo " selected='Selected'";
														}
														echo ">".$temp['Type']."</option>
														";
													}															
													/*
													$TypeSearch=mysql_query("SELECT * FROM types");
												
													while($Typerow = mysql_fetch_array($TypeSearch)){	
														echo "<option value='".$Typerow['ID']."'";
														if($Typerow['ID']==$Type2){
															echo " selected='Selected'";
														}
														echo ">".$Typerow['Type']."</option>
														";
													}											
													*/
													echo "</select>											
												</td>												
												
												<td>
													<b>Source: </b>
												</td>
												<td style='padding-right:10px;'>													
													<select style='width:100%;' name='Source'>
													<option value=''";
													if($Source2 == ""){
														echo " selected='Selected'";
													}
													echo ">All</option>";
													
													foreach($GAMESOURCES as $temp){
														echo "<option value='".$temp['ID']."'";
														if($temp['ID']==$Source2){
															echo " selected='Selected'";
														}
														echo ">".$temp['Source']."</option>
														";
													}														
													/*
													$SourceSearch=mysql_query("SELECT * FROM source");
												
													while($Sourcerow = mysql_fetch_array($SourceSearch)){	
														echo "<option value='".$Sourcerow['ID']."'";
														if($Sourcerow['ID']==$Source2){
															echo " selected='Selected'";
														}
														echo ">".$Sourcerow['Source']."</option>
														";
													}											
													*/
													echo "</select>
													
												</td>
												<td>
													<b>Graphics:</b>
												</td>
												<td style='padding-right:10px;'>													
													<select style='width:100%;' name='Graphics'>
													<option value=''";
													if($Graphics2 == ""){
														echo " selected='Selected'";
													}
													echo ">All</option>";		

													foreach($GAMEGRAPHICS as $temp){
														echo "<option value='".$temp['ID']."'";
														if($temp['ID']==$Graphics2){
															echo " selected='Selected'";
														}
														echo ">".$temp['Graphics']."</option>
														";
													}														
													
													/*
													$GraphicSearch=mysql_query("SELECT * FROM graphics");
												
													while($Graphicsrow = mysql_fetch_array($GraphicSearch)){	
														echo "<option value='".$Graphicsrow['ID']."'";
														if($Graphicsrow['ID']==$Graphics2){
															echo " selected='Selected'";
														}
														echo ">".$Graphicsrow['Graphics']."</option>
														";
													}											
													*/
														
													echo "</select>
													
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
													<option value=''";
													if($Release2 == ""){
														echo " selected='Selected'";
													}
													echo ">All</option>";
													
													
													foreach($GAMERELEASE as $temp){
														echo "<option value='".$temp['ID']."'";
														if($temp['ID']==$Release2){
															echo " selected='Selected'";
														}
														echo ">".$temp['Release']."</option>
														";
													}														
													/*
													$ReleaseSearch=mysql_query("SELECT * FROM `release`");
												
													while($Releaserow = mysql_fetch_array($ReleaseSearch)){	
														echo "<option value='".$Releaserow['ID']."'";
														if($Releaserow['ID']==$Release2){
															echo " selected='Selected'";
														}
														echo ">".$Releaserow['Release']."</option>
														";
													}											
													*/
													echo "</select>	
												</td>
												<td colspan='4'>
												</td>
												<td>
													<b>Items Per Page:</b>
												</td>
												<td style='padding-right:15px;'>													
													<input style='width:100%;' type=\"text\" name=\"IPP\" size=\"10\" value='".$IPP2."' />
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
															<input type=checkbox name='Mode".$temp['ID']."'";
															if(isset($_CLEANREQUEST['Mode'.$temp['ID']])){
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
															<input type=checkbox name='Plat".$temp['ID']."'";
															if(isset($_CLEANREQUEST['Plat'.$temp['ID']])){
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
											
											<tr>
												<td colspan='8'>
												<div style='height:5px;'></div>
												</td>
											</tr>
											

											
											<tr>
												<td colspan='8'>
													<table width='100%'>
														<colgroup width='*'></colgroup>
														<colgroup width='110'></colgroup>
														<colgroup width='110'></colgroup>
														<colgroup width='*'></colgroup>
													<tr>
													<td></td>
													<td>
													<input type=\"submit\" class=\"defaultsink\" name=\"Filter\" value=\"Filter\" />
													<input style='width:200px;' type=\"submit\" name='ClearFilters' value=\"Clear Filters\" />
													</td>
													<td><input style='width:200px;' type=\"submit\" name='Filter' value=\"Filter\" /></td>
													<td></td>
													</tr>
													</table>
												</td>
											</tr>
											</table>
										</form>
									</div>
								</div>
								</div>
									
								
								
								</td>
							</tr>
							</table>
							
							
							
							<table width='100%'>
								
								<colgroup width='*'></colgroup>
							<tr>
								
								<td class='AlignTop'>	
						
						";
						$ResultsReturned = false;
						$ResultNumber = 0;

						$GameSearch = $Gamedb->prepare("SELECT * FROM freegames ".$SearchLine."LIMIT ".(($Page-1)*$IPP2).",".$IPP2);
						$GameSearch->execute();						
						
						while($row = $GameSearch->fetch(PDO::FETCH_ASSOC)){
							
							$ResultsReturned = true;
							$ResShot = "NoArtwork/NoShot.png";
							$NoRes = true;
							$b = 4;
							do{
								$ResSearch = $Gamedb->prepare("SELECT * FROM resources WHERE ID=:id AND Type=:b");
								$ResSearch->bindValue(':id', $row['ID'], PDO::PARAM_INT);
								$ResSearch->bindValue(':b', $b, PDO::PARAM_INT);
								$ResSearch->execute();	
								//$ResSearch=mysql_query("SELECT * FROM resources WHERE ID='".$row['ID']."' AND Type='".$b."'");
								//if($Resrow = mysql_fetch_array($ResSearch)){
								if($Resrow = $ResSearch->fetch(PDO::FETCH_ASSOC)){
									$ResShot = $Resrow['ResThumb'];
									$ResShot2 = $Resrow['ResourcePath'];
									$NoRes = false;
								}
								switch($b){
									case 1: 
										$NoRes = false;
										break;
									case 3:
										$b = 1;
										break;
									case 4:
										$b = 3;
										break;
								}
							}while($NoRes == true);
							echo "
								<a class='gamelistlink' href='".$DOMAIN."/Games/".$row['ID']."/".$row['Name']."/'><div class='news' >
									<div style=\"margin:5px;\" >	
										<table width='100%'>
											<colgroup width='200px'></colgroup>
											<colgroup width='30px'></colgroup>
											<colgroup width='*'></colgroup>
										<tr>
											<td class='AlignTop'>
											";
											if($row['Age']!=2 || calculate_age($user->data['user_birthday'])>17 || $FDBPrivilages["V18+Game"]==2){
												echo "<img style='max-height: 220px; max-width: 200px;' src='".artwork_correct($ResShot)."' />";
											}else{
												echo "<img style='max-height: 220px; max-width: 200px;' src='".artwork_correct("NoArtwork/18Content.png")."' />";
											}
											echo "
											</td>
											<td></td>
											<td class='AlignTop'>
											<table width='100%'>
												<colgroup width='*'></colgroup>
												<colgroup width='100px'></colgroup>
												<colgroup width='90px'></colgroup>
												<tr>
													<td><NewsTitle><a style='text-decoration:none;' href='".$DOMAIN."/Games/".$row['ID']."/".$row['Name']."/'>".$row['Name']."</a>";
													if($row['Release']==1){
														echo " (Alpha)";
													}elseif($row['Release']==3){
														echo " (Beta)";
													}
													echo "</NewsTitle></td>
													<td class='right' style='padding-right:10px'><NewsTitle>".round($row['Rating'],1)." / 10</NewsTitle></td>
													<td>(".$row['RateNum']." Ratings)</td>
												</tr>
											</table>";
											
											if($row['Age']!=2 || calculate_age($user->data['user_birthday'])>17 || $FDBPrivilages["V18+Game"]==2){
												echo "
												<p>";
												if(strlen($row['About']) > 340){
													echo substr($row['About'],0,340)."... ";
												}else{
													echo $row['About'];
												}
												echo "
												</p>
												";
											}else{
												echo "
												<p>
												18+ Content, please login to view<br/>
												(Note: You must set your birthday in your forum profile)
												</p>
												";
											}
											
											echo "
											<hr />
											<table width='100%'>
												<colgroup width='100'></colgroup>
												<colgroup width='485'></colgroup>
												<colgroup width='100'></colgroup>
												<colgroup width='85'></colgroup>												
											<tr>
												<td>
													<b>Modes: </b>
												</td>
												<td>";
													$Mode = "";
													foreach($GAMEMODESBITS as $temp){
														if($temp['ID'] & $row['ModeBITS']){
															$Mode = $Mode.$temp['Mode'].", ";
														}
													}
													$Mode = substr($Mode,0,-2);
													echo $Mode;
													echo "
												</td>
												
												<td>
													<b>Graphics: </b>
												</td>
												<td>
													";

												foreach($GAMEGRAPHICS as $temp){
													if($temp['ID']==$row['Graphics']){
														echo $temp['Graphics'];
													}
												}													
									
												/*
												<td>
													<b>Source: </b>
												</td>
												<td>";
												
												foreach($GAMESOURCES as $temp){
													if($temp['ID']==$row['Source']){
														echo $temp['Source'];
													}
												}															
								
												echo "</td>	
												*/
									
												echo "
												</td>
												
												
																							
											</tr>
											<tr>
												<td>
													<b>Platforms: </b>
												</td>
												<td>";
													$Platform = "";
													foreach($GAMEPLATFORMSBITS as $temp){
														if($temp['ID'] & $row['PlatformBITS']){
															$Platform = $Platform.$temp['Platform'].", ";
														}
													}
													$Platform = substr($Platform,0,-2);	
													echo $Platform;
												echo "
												</td>
												
												
												
												
												<td>
													<b>Type: </b>
												</td>
												<td>";
												
												foreach($GAMETYPES as $temp){
													if($temp['ID']==$row['Type']){
														echo $temp['Type'];
													}
												}													
												/*
												$TypeSearch=mysql_query("SELECT * FROM types WHERE ID='".$row['Type']."'");
												if($Typerow = mysql_fetch_array($TypeSearch)){
													echo $Typerow['Type'];
												}
												*/
												echo "
												</td>
											</tr>
											<tr>
												<td>
													<b>Genres:</b>
												</td>
												<td colspan='3'>
													";
												
													$Genre = "";
													foreach($GAMEGENRESBITS as $temp){
														if($temp['ID'] & $row['GenreBITS']){
															$Genre = $Genre.$temp['Genre'].", ";
														}
													}
													$Genre = substr($Genre,0,-2);
													echo $Genre;
												/*
												foreach($GAMEGENRES as $temp){
													if($temp['ID']==$row['Genre']){
														echo $temp['Genre'];
													}
												}	
												*/
												
												echo "
												</td>
												
												
											
											</tr>
											</table>
											</td>
										</tr>
										</table>
									</div>
								</div></a>
							";
						}
						
						if($ResultsReturned == false){
							echo "
							<div class='news' >
								<div class='center' style=\"margin:5px;\" >
									<b>No Games Matched Your Search Criteria</b>
								</div>
							</div>";
						}
						
						echo "
								</td>
							</tr>
						</table>						
							<div class='news' >
								<div style=\"margin:5px;\" >
									<table width='100%'>
										<colgroup width='*'></colgroup>
										<colgroup width='100'></colgroup>
										<colgroup width='50'></colgroup>
										<colgroup width='160'></colgroup>
										<colgroup width='50'></colgroup>										
										<colgroup width='100'></colgroup>
										<colgroup width='*'></colgroup>
									<tr>
										<td></td>
										<td>
								";
						
						if($Page==1){
						
						}else{
							echo "
								<form name='input' action=\"Games.php\" method=\"get\">
										<input type='hidden' name='Page' value='".($Page-1)."'>";
										foreach($_CLEANREQUEST as $key => $value){
											if($key!="Page"){
												echo "<input type='hidden' name='".$key."' value='".$value."'>";
											}
										}																				
										echo "<input type='submit' style='width:150px;' value='Prev'>
									</form> 									
								";														
						}
						
						echo "			</td>
										<td></td>
										<td>
										
											<form name='input' action=\"Games.php\" method=\"get\">
											Page: <input style='width:30px;' type=\"text\" name=\"Page\" value=\"".$Page."\" /> / ".$MaxPNum."  ";
											foreach($_CLEANREQUEST as $key => $value){
												if($key!="Page"){
													echo "<input type='hidden' name='".$key."' value='".$value."'>";
												}
											}											
																									
											echo "<input type=\"submit\" value=\"Go\" />
											</form> 										
											
										</td>
										<td></td>
										<td>
										";

						if($Page==$MaxPNum){
						
						}else{
							
							echo "
	
								<form name='input' action=\"Games.php?".substr($GETString,0,-1)."\" method=\"get\">
										<input type='hidden' name='Page' value='".($Page+1)."'>";
										foreach($_CLEANREQUEST as $key => $value){
											if($key!="Page"){
												echo "<input type='hidden' name='".$key."' value='".$value."'>";
											}
										}							
										echo "<input type='submit' style='width:150px;' value='Next'>
									</form> 						
							";
						}

						
						echo "
										</td>
										<td></td>
									</tr>
								</table>
								</div>
							</div>
						";						
						
						
						
						//mysql_close($Connect);
					?>
						
<?php 
	include("./Includes/Footer.php");
	include("./Includes/FooterFunctions.php");
?>
