<?php
$PageName = "Sitemap.php";
include("./Includes/HeaderFunctions.php");
header("Content-type: text/xml");

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"
        xmlns:image=\"http://www.google.com/schemas/sitemap-image/1.1\"
        xmlns:video=\"http://www.google.com/schemas/sitemap-video/1.1\">";


$stmt = $Gamedb->prepare("SELECT * FROM freegames WHERE `QuedTodaysGame`=0");
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	echo "
    <url>
        <loc>http://tfgdb.com/Games/".$row['ID']."/".name_correct_XML($row['Name'])."/</loc>
    </url>	
";
}																							

$Pages=array("index.php","AboutUs.php","API.php","Bundles.php","Games.php","Login.php"); 

foreach ($Pages as $value) {
echo "
    <url>
        <loc>http://tfgdb.com/".$value."</loc>
    </url>	
";
}	
																						
echo "
</urlset>
";

include("./Includes/FooterFunctions.php");
?>