<?php
$MetaDescription = "View our games database by categories";
$MetaKeywords = "Free,games,open,source,closed,commercial,database,genres,categories";
$MetaTitle = "Genres - TheFreeGamesDB";
$PageName = "Genres.php";

include("./Includes/HeaderFunctions.php");
$MetaTitle = "Genres - ".$DOMAINTITLE;
include("./Includes/Header.php");

echo "
<h1>Game Categories</h1>
";				

echo "
<div class='news'>
<table width='100%'>
	<colgroup width='*'></colgroup>
	<colgroup width='300px'></colgroup>
	<colgroup width='300px'></colgroup>
	
	<colgroup width='*'></colgroup>
<tr>
	<td></td>
	<td class='AlignTop'>
		<table width='100%'>
		<tr>
			<td><H3>Genres</H3></td>
		</tr>
		<tr>
			
			<td class='center'>
				<a href='Games.php'>All Genres</a>
			</td>
			
		</tr>								
		";
		$stmt = $Gamedb->prepare("SELECT * FROM genresbits");
		$stmt->execute();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		echo "
		<tr>
			
			<td class='center'>
				<a href='Games.php?Gen".$row['ID']."=on'>".$row['Genre']."</a>
			</td>
			
		</tr>
				";
		}
		echo "
		</table>
	</td>
	<td class='AlignTop'>
		<table  width='100%'>";
											
		echo "
		<tr>
			<td><H3>Platform</H3></td>
		</tr>
		";
		$stmt = $Gamedb->prepare("SELECT * FROM platformbits");
		$stmt->execute();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		echo "
		<tr>
			<td class='center'>
				<a href='Games.php?Plat".$row['ID']."=on'>".$row['Platform']."</a>
			</td>
		</tr>
		";
		}
		echo "
		</table>
		
		
	</td>
	
	<td></td>
</tr>

</table>
<br/>
</div>

";
include("./Includes/Footer.php");
include("./Includes/FooterFunctions.php");
?>