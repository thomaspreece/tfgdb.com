<?php
require($_SERVER['DOCUMENT_ROOT']."/Includes/HeaderFunctions.php");
																								
$stmt = $Gamedb->prepare("SELECT * FROM freegames WHERE 1");
$stmt->execute();
																							
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
echo $row['Name']."
";	
}

require($_SERVER['DOCUMENT_ROOT']."/Includes/FooterFunctions.php");
?>