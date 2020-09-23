<?php
require($_SERVER['DOCUMENT_ROOT']."/Includes/HeaderFunctions.php");
																								
$stmt = $Gamedb->prepare("SELECT * FROM genresbits WHERE 1 ORDER BY `ID` ASC");
$stmt->execute();
																							
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
echo $row['Genre']."
";	
}

require($_SERVER['DOCUMENT_ROOT']."/Includes/FooterFunctions.php");
?>