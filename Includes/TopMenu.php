<script>
		$(function() {
			var availableTags = [
			<?php 
																								
			$stmt = $Gamedb->prepare("SELECT * FROM freegames WHERE `QuedTodaysGame`=0 ");
			$stmt->execute();
			
			
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			echo "\"".strtolower(str_replace("&amp;","&",str_replace("&#039;","'",$row['Name'])))."\",";
			}
			echo "\" \""
			
			?>
			];
			$( "#SearchGameID" ).autocomplete({
				source: function(request, response) {
					var results = $.ui.autocomplete.filter(availableTags, request.term);
					results.sort();
					
					var bestResults = new Array();
					var midResults = new Array();
					var poorResults = new Array();
					for (var i = 0; i < results.length; i++) {
						var posResult = results[i].toLowerCase().search( request.term.toLowerCase() );
						var posResult2 = results[i].toLowerCase().search(" "+request.term.toLowerCase() );
						
						if(posResult == 0){
							bestResults.push(results[i]);
						}else if( posResult2 == -1 ){
							poorResults.push(results[i]);
						}else{
							midResults.push(results[i]);
						}
					}
					results = bestResults.concat(midResults,poorResults);
					response(results.slice(0, 15));
				},
				position: { my : "right top", at: "right bottom" },
				minLength: 1,
				autoFocus: false
				
			});
		});
	</script>


<div style='background-image: url(<?php echo $DOMAIN;?>/Resources/BackGrad.png) ; background-color: #3f3f59; position: fixed; left:0px; top:0px;padding:0px;margin:0px;border:0px;width:100%;text-align:center;z-index:1000;opacity:0.95;filter:alpha(opacity=95);height:50px;'>

<div style='display:inline-block;width:996px;*display:inline;*zoom:1;text-align:left;height:50px;'>
	<div style='float:left;height:100%;margin-left:10px;margin-right:10px;'>
		<table style='height:100%;'>
			<tr>
				<td style='vertical-align: middle;'>
					<div style='width:150px'>
							<?php
								
								echo "<a href='".$DOMAIN."/index.php'><img title='The Free Games Database - ".$GAMECOUNT." Games And Counting...' src='".$DOMAIN."/Resources/TopTitleBar.png' style='width:150px;' /></a>";
							?>
					</div>
				</td>
			<tr>
		</table>
	</div>
<?php



echo "

	<div style='float:left;'>
		<ul id=\"TopMenuNav\">
				<li><a href='#'>Browse Games</a>
				<div class='TopMenuContent' id='TopMenuContent1'>
				<div class='TopMenuContentBar'></div>
					<table cellspacing=0px>
					<tr>
						<td>
							<a class='Menu1Link-2' href='".$DOMAIN."/Games.php'><div class='MenuContent'>
								<div style='height:20px;'></div>
								<img style='display: block; margin-left: auto; margin-right: auto' width='60px' src='".$DOMAIN."/Resources/menu_allgames.png' alt='All Games' />
								All Games
								<div style='height:20px;'></div>
								</div>
							</a>
							<a class='Menu1Link-2' href='".$DOMAIN."/Random.php'>
								<div class='MenuContent'>
								<div style='height:20px;'></div>
								<img style='display: block; margin-left: auto; margin-right: auto' width='60px' src='".$DOMAIN."/Resources/menu_random.png' alt='Random Game' />
								Random Game
								<div style='height:20px;'></div>
								</div>
							</a>
						</td>
						<td>
				";
					$itemNum = 0;
					foreach($GAMEGENRESBITS as $temp){
						echo "
						<div class='MenuContent'><a class='Menu1Link' href='".$DOMAIN."/Games.php?Gen".$temp['ID']."=on'>".$temp['Genre']."</a></div>
						";
						$itemNum = $itemNum + 1;
						if($itemNum > 7){
							$itemNum = 0;
							echo "</td><td>";
						}
					}
				echo "
						</td>
					</tr>
					</table>
				</div>
				</li>
				
				<li><a href='#'>Contribute</a>
				<div class='TopMenuContent' id='TopMenuContent2'>
				<div class='TopMenuContentBar'></div>
					<table cellspacing=0px>
						<tr>
							<td>
								
								<a class='Menu2Link' href='".$DOMAIN."/AddGame/'>
								<div class='MenuContent'>
								
								<div style='height:20px;'></div>
								<img style='display: block; margin-left: auto; margin-right: auto' width='60px' src='".$DOMAIN."/Resources/menu_add.png' alt='Add Game' />
								Add Game
								<div style='height:20px;'></div>
								</div></a>
								
							</td>
							";
							if(ISSET($_GET["ID"])){
							echo "
								<td>
									<a class='Menu2Link' href='".$DOMAIN."/Edit/".$_GET["ID"]."//'>
									<div class='MenuContent'>
									<div style='height:20px;'></div>
									<img style='display: block; margin-left: auto; margin-right: auto' width='60px' src='".$DOMAIN."/Resources/menu_edit.png' alt='Edit Game' />
									Edit Game
									<div style='height:20px;'></div>
									</div>
									</a>
								</td>
							";
							}else{
							echo "	
								<td>
									<a class='Menu2Link' href=''>
									<div class='MenuContent'>
									<div style='height:20px;'></div>
									<img style='display: block; margin-left: auto; margin-right: auto' width='60px' src='".$DOMAIN."/Resources/menu_edit.png' alt='Edit Game' />
									You must be viewing a game entry to edit it
									
									</div>
									</a>
								</td>
							";
							}
							echo "
						</tr>
					</table>
				
				</div>
				</li>
				
				<li><a href='#'>Community</a>
				<div class='TopMenuContent' id='TopMenuContent5'>
				<div class='TopMenuContentBar'></div>
					<table cellspacing=0px>
						<tr>
							<td>
								<a class='Menu2Link' href='".$DOMAIN."/Forum/'>
								<div class='MenuContent'>
								
								<div style='height:20px;'></div>
								<img style='display: block; margin-left: auto; margin-right: auto' width='60px' src='".$DOMAIN."/Resources/menu_forum_9.png' alt='Forum' />
								Forum
								<div style='height:20px;'></div>
								</div></a>
								
							</td>
							<td>
								<a class='Menu2Link' href='https://www.facebook.com/pages/The-Free-Games-Database-tfgdbcom/122542944583665'>
								<div class='MenuContent'>
								<div style='height:20px;'></div>
								<img style='display: block; margin-left: auto; margin-right: auto' width='60px' src='".$DOMAIN."/Resources/menu_facebook_2.png' alt='Facebook' />
								Facebook
								<div style='height:20px;'></div>
								</div>
								</a>
							</td>
						</tr>
					</table>
				
				</div>
				</li>				
				
				
				
				
				";
				if($user->data['is_registered']){
					echo "
					<li><a style='width:180px' href='#'>".$user->data['username']." [".substr($user->data['user_points'], 0, -3)."]</a>
						<div class='TopMenuContent' id='TopMenuContent3'>
						<div class='TopMenuContentBar'></div>
							<table cellspacing=0px>
								<tr>
									<td>
										<div style='height:30px;'></div>
										<div class='MenuContent'><a class='Menu3Link' href='".$DOMAIN."/Forum/points.php?mode=info'>What are points?</a></div>
									
										<div class='MenuContent'><a class='Menu3Link' href='".$DOMAIN."/Forum/shop.php?mode=cat'>Spend earned points (".substr($user->data['user_points'], 0, -3).")</a></div>
										<div style='height:30px;'></div>
									</td>
									
									<td>
										
										<a class='Menu3Link-2' href=\"".$DOMAIN."/Forum/ucp.php?i=pm&folder=inbox\">
											<div class='MenuContent'>
											<div style='height:20px;'></div>
											";
											
											if($user->data['user_new_privmsg']==0){
												echo "
												<img style='display: block; margin-left: auto; margin-right: auto' width='60px' src='".$DOMAIN."/Resources/menu_mail_closed.png' alt='Mail' />
												You have no new messages
												";
											}else{
												echo "
												<img style='display: block; margin-left: auto; margin-right: auto' width='60px' src='".$DOMAIN."/Resources/menu_mail_open.png' alt='Mail' />
												You have new messages
												";
											}
											
											echo "
											<div style='height:20px;'></div>
											</div>
										</a>
										
									</td>	
									
									<td>
										
									";
									
																	
									
									if($FDBPrivilages['VAPanel']==2 || $auth->acl_get('m_') || $auth->acl_get('a_')){
										echo "<div class='MenuContent'><a class='Menu3Link' href=\"".$DOMAIN."/Forum/ucp.php\">User Control Panel</a></div>";
										if($FDBPrivilages['VAPanel']==2){
											echo "<div class='MenuContent'><a class='Menu3Link' href=\"".$DOMAIN."/Admin.php\">Games Moderator Area</a></div>";
										}
										if ($auth->acl_get('m_')){
											echo "<div class='MenuContent'><a class='Menu3Link' href=\"".$DOMAIN."/Forum/mcp.php?i=main&mode=front\">Forum Moderator Area</a></div>";
										}
										if ($auth->acl_get('a_')){
											echo "<div class='MenuContent'><a class='Menu3Link' href=\"".$DOMAIN."/Forum/adm/index.php\">Administration Area</a></div>";
										}
									}else{
										echo "
										<a class='Menu3Link-2' href=\"".$DOMAIN."/Forum/ucp.php\">
											<div class='MenuContent'>
											<div style='height:20px;'></div>
											<img style='display: block; margin-left: auto; margin-right: auto' width='60px' src='".$DOMAIN."/Resources/menu_controlpanel.png' alt='User Control Panel' />
											User Control Panel
											<div style='height:20px;'></div>
											</div>
										</a>
										
										
										";
									}
									
									echo "
									</td>
																
									<td>
										
										<a class='Menu3Link-2' href=\"".$DOMAIN."/Forum/ucp.php?mode=logout&sid=".$user->data['session_id']."&redirect=".$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"]."\">
											<div class='MenuContent'>
											<div style='height:20px;'></div>
											<img style='display: block; margin-left: auto; margin-right: auto' width='60px' src='".$DOMAIN."/Resources/menu_logout.png' alt='Logout' />
											Logout
											<div style='height:20px;'></div>
											</div>
										</a>
										
										
									</td>
								</tr>
							</table>
						
						</div>
					</li>	
					";
				
					
					
				}else{
					echo "
					<li><a style='width:180px' href='".$DOMAIN."/Login.php?Redirect=".$_SERVER["PHP_SELF"]."?".htmlspecialchars($HTTP_SERVER_VARS['QUERY_STRING'])."'>Login/Register</a>
					
					</li>
					";
				}			
				
				echo "
						
		</ul>
	</div>

	
	<div style='float:right;height:100%'>
		<div style='position:relative;height:30px;top:50%'>
			<div style='position:relative; top:-13px; margin-right:10px'>
		
					<form id='SearchGameForm' name='searchform' action='".$DOMAIN."/Games.php' method='get'>
						<input type='text' name='Search' id='SearchGameID' value='' style='width:160px;height:25px;margin-right: 10px;border:2px;padding:0px 0px 0px 25px;-moz-border-radius: 8px;-webkit-border-radius: 8px;border-radius: 8px;-khtml-border-radius: 8px;background: url('".$DOMAIN."/Resources/SearchBar.png') no-repeat scroll 6px 4px white;' />
						
						
											
					</form> 
			</div>
		</div>
	</div>

</div>



</div>

";
/*
<div class='TopMenuContent' id='TopMenuContent4' style='width:350px'>
					<div class='TopMenuContentBar'></div>
					
						<form style='margin:10px' action=\"".$DOMAIN."/Forum/ucp.php?mode=login\" method=\"post\" enctype=\"multipart/form-data\">
							


								<input type=\"hidden\" name=\"redirect\" value=\"".$_SERVER["PHP_SELF"]."?".htmlspecialchars($HTTP_SERVER_VARS['QUERY_STRING'])."\" />
								<table width='100%'>
									<colgroup width='*'></colgroup>
									<colgroup width='100px'></colgroup>
									<colgroup width='200px'></colgroup>
									<colgroup width='*'></colgroup>
								<tr>
									<td></td>
									<td>Username: </td>
									<td>
										<input style='width:200px;' type=\"text\" name=\"username\" size=\"10\" /><br/>
										
									</td>
									<td></td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td>		
										<div><a href='".$DOMAIN."/Forum/ucp.php?mode=register' style='text-decoration: underline;'>Register</a></div>
									</td>
									<td></td>
								</tr>
								<tr>
									<td></td>
									<td>Password: </td>
									<td>		
										<input style='width:200px;' type=\"password\" id=\"password\" name=\"password\" size=\"10\" class=\"inputbox autowidth\" /><br/>
										
									</td>
									<td></td>
								</tr>	
								
								</table>

							<div class='center' style='width:100%;margin:10px;'><input style='width:250px;' type=\"submit\"  value=\"Login\" name=\"login\" /></div>
						</form>

					</div>
*/					
?>
